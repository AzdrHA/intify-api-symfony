<?php

namespace App\Controller\Guild;

use App\Controller\DefaultApiController;
use App\ServiceApi\Guild\GuildInviteService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/guilds/invites")
 */
class GuildInviteController extends DefaultApiController
{
    /**
     * @Rest\Post("")
     * @param Request $request
     * @param GuildInviteService $guildInviteService
     * @return JsonResponse
     */
    public function createGuildInvite(Request $request, GuildInviteService $guildInviteService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $guildInviteService,
            'function' => 'createGuildInvite',
            'args' => []
        ]);
    }

    /**
     * @Rest\Post("/{code}")
     * @param Request $request
     * @param string $code
     * @param GuildInviteService $guildInviteService
     * @return JsonResponse
     */
    public function addGuildMember(Request $request, string $code, GuildInviteService $guildInviteService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $guildInviteService,
            'function' => 'addGuildMember',
            'args' => [$code]
        ]);
    }
}