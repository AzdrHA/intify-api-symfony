<?php

namespace App\Controller\Channel;

use App\Controller\DefaultApiController;
use App\ServiceApi\Channel\ChannelService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/users/@me/channels")
 */
class ChannelUserController extends DefaultApiController
{
    /**
     * @Rest\Post("")
     * @param Request $request
     * @param ChannelService $channelService
     * @return JsonResponse
     */
    public function createPrivateMessage(Request $request, ChannelService $channelService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $channelService,
            'function' => 'createPrivateMessage',
            'args' => []
        ]);
    }

    /**
     * @Rest\Get("")
     * @param Request $request
     * @param ChannelService $channelService
     * @return JsonResponse
     */
    public function getPrivateMessage(Request $request, ChannelService $channelService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $channelService,
            'function' => 'getPrivateMessage',
            'args' => []
        ]);
    }
}