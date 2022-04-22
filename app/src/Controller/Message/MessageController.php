<?php

namespace App\Controller\Message;

use App\Controller\DefaultApiController;
use App\Entity\Channel\Channel;
use App\ServiceApi\Message\MessageService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Rest\Route ("/channels/{channel}", requirements={"channel":"\d+"})
 * @ParamConverter("channel", class="App\Entity\Channel\Channel", options={"id"="channel"})
 */
class MessageController extends DefaultApiController
{
    /**
     * @Rest\Post("/messages")
     * @param Request $request
     * @param Channel $channel
     * @param MessageService $messageService
     * @return JsonResponse
     */
    public function createMessage(Request $request, Channel $channel, MessageService $messageService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $messageService,
            'function' => 'createMessage',
            'args' => [$channel]
        ]);
    }

    /**
     * @Rest\Get ("/messages")
     * @param Request $request
     * @param Channel $channel
     * @param MessageService $messageService
     * @return JsonResponse
     */
    public function getMessageByChannel(Request $request, Channel $channel, MessageService $messageService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $messageService,
            'function' => 'getMessageByChannel',
            'args' => [$channel]
        ]);
    }
}