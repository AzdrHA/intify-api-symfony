<?php

namespace App\Controller\Guild;

use App\Controller\DefaultApiController;
use App\Entity\Guild\Guild;
use App\ServiceApi\Channel\ChannelService;
use App\ServiceApi\Guild\GuildService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @Rest\Post("/{guild}/channels", requirements={"guild":"\d+"})
     * @ParamConverter("guild", class="App\Entity\Guild\Guild", options={"id"="guild"})
     * @param Request $request
     * @param Guild $guild
     * @param ChannelService $channelService
     * @return JsonResponse
     */
    public function createGuildChannel(Request $request, Guild $guild, ChannelService $channelService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $channelService,
            'function' => 'createGuildChannel',
            'args' => [$guild]
        ]);
    }
}