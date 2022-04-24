<?php

namespace App\ServiceApi\Message;

use App\Entity\Channel\Channel;
use App\Entity\File\File;
use App\Entity\Message\Message;
use App\Entity\Message\MessageAttachment;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Message\MessageCreateType;
use App\Manager\Message\MessageManager;
use App\Service\Mercure\MercureService;
use App\Service\Message\MessageService as BaseMessageService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use App\Utils\UtilsNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\ParamInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MessageService extends DefaultService
{
    private UserService $userService;
    private MessageManager $messageManager;
    private BaseMessageService $messageService;
    private MercureService $mercureService;
    private SluggerInterface $slugger;
    private ParameterBagInterface $param;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory, UserService $userService, MessageManager $messageManager,
        BaseMessageService $messageService, MercureService $mercureService, SluggerInterface $slugger,
        ParameterBagInterface $param, EntityManagerInterface $entityManager
    ){
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->messageManager = $messageManager;
        $this->messageService = $messageService;
        $this->mercureService = $mercureService;
        $this->slugger = $slugger;
        $this->param = $param;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return array
     * @throws ApiException
     * @throws ApiFormErrorException
     * @throws ExceptionInterface
     */
    public function createMessage(Request $request, Channel $channel): array
    {
        if (!$channel->isText())
            throw new ApiException('is not a text channel');

        $message = new Message();

        $callback = function () use ($message, $channel, $request)
        {
            $file = $request->files->get('file');

            if ($file) {

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->param->get('file_directory'),
                        $newFilename
                    );
                    $file = new File();
                    $file->setName($originalFilename);
                    $file->setSize(10);
                    $file->setPath($this->param->get('file_directory') . $newFilename);
                    $this->entityManager->persist($file);

                    $messageAttachment = new MessageAttachment();
                    $messageAttachment->setFile($file);
                    $messageAttachment->setMessage($message);
                    $this->entityManager->persist($file);
                    $this->entityManager->flush();
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
//                $message->setMessageAttachments($newFilename);
            }
            $message->setOwner($this->userService->getUserOrException());
            $message->setChannel($channel);
            $this->messageManager->save($message);
            $this->mercureService->makeRequest(sprintf(MercureService::CREATE_MESSAGE, $channel->getId()), $this->messageService->serializeMessage($message));
        };

        $this->handleForm($request, MessageCreateType::class, $message, $callback);

        return $this->messageService->serializeMessage($message);
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return array
     */
    public function getMessageByChannel(Request $request, Channel $channel): array
    {
        $messages = $this->messageManager->getRepository()->createQueryBuilder('m')
            ->addSelect('owner', 'members')
            ->leftJoin('m.owner', 'owner')
            ->leftJoin('owner.guildMembers', 'members')
            ->leftJoin('members.guild', 'guild')
            ->leftJoin('m.channel', 'channel')
            ->where('channel.id = :channel_id')
            ->andWhere('guild.id = :guild_id')
            ->setParameters([
                'channel_id' => $channel->getId(),
                'guild_id' => $channel->getGuild()->getId()
            ])
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        UtilsNormalizer::normalizeArray($messages);

        return $messages;
    }
}