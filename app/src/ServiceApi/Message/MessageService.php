<?php

namespace App\ServiceApi\Message;

use App\Entity\Channel\Channel;
use App\Entity\Message\Message;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Message\MessageCreateType;
use App\Manager\Message\MessageManager;
use App\Service\Mercure\MercureService;
use App\Service\Message\MessageService as BaseMessageService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use App\Utils\UtilsNormalizer;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageService extends DefaultService
{
    private UserService $userService;
    private MessageManager $messageManager;
    private BaseMessageService $messageService;
    private MercureService $mercureService;

    public function __construct(
        FormFactoryInterface $formFactory, UserService $userService, MessageManager $messageManager,
        BaseMessageService $messageService, MercureService $mercureService
    ){
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->messageManager = $messageManager;
        $this->messageService = $messageService;
        $this->mercureService = $mercureService;
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

        $callback = function () use ($message, $channel)
        {
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