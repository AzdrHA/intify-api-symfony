<?php

namespace App\ServiceApi\Message;

use App\Entity\Channel\Channel;
use App\Entity\Message\Message;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Message\MessageCreateType;
use App\Manager\Message\MessageManager;
use App\Service\Message\MessageService as BaseMessageService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageService extends DefaultService
{
    private UserService $userService;
    private MessageManager $messageManager;
    private BaseMessageService $messageService;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserService $userService
     * @param MessageManager $messageManager
     * @param BaseMessageService $messageService
     */
    public function __construct(
        FormFactoryInterface $formFactory, UserService $userService, MessageManager $messageManager,
        BaseMessageService $messageService
    ){
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->messageManager = $messageManager;
        $this->messageService = $messageService;
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
        return $this->messageManager->getRepository()->createQueryBuilder('m')
            ->addSelect('owner')
            ->leftJoin('m.owner', 'owner')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}