<?php

namespace App\Controller\Guild;

use App\Controller\DefaultApiController;
use App\Service\Guild\GuildService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/guilds")
 */
class GuildController extends DefaultApiController
{
    /**
     * @Rest\Post("")
     * @param Request $request
     * @param GuildService $guildService
     * @return JsonResponse
     */
    public function createGuild(Request $request, GuildService $guildService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $guildService,
            'function' => 'createGuild',
            'args' => []
        ]);
    }
}