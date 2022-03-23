<?php

namespace App\Service\Channel;

use App\Entity\Channel\Channel;
use App\Service\Guild\GuildService;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ChannelService
{
    private GuildService $guildService;
    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }


    /**
     * @throws ExceptionInterface
     */
    public function serializeChannel(Channel $channel): array
    {
        $whiteList = ['id', 'type', 'name', 'topic', 'position', 'parent', 'recipients'];
        $res = UtilsNormalizer::normalize($channel, [], [], $whiteList);
        $res['guild'] = $this->guildService->serializeGuild($channel->getGuild());

        return $res;
    }
}