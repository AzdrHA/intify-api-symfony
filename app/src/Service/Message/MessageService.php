<?php

namespace App\Service\Message;

use App\Entity\Channel\Channel;
use App\Entity\Message\Message;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Message\MessageCreateType;
use App\Manager\Message\MessageManager;
use App\Service\DefaultService;
use App\Service\User\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageService extends DefaultService
{
    private UserService $userService;
    private MessageManager $messageManager;
    public function __construct(FormFactoryInterface $formFactory, UserService $userService, MessageManager $messageManager)
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->messageManager = $messageManager;
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

        return $this->normalizeSingle($message);
    }
}