<?php

namespace App\Service\Mercure\Guild;

use App\Entity\Guild\Guild;
use App\Entity\User\User;
use App\Service\Mercure\MercureService;

class GuildChannelMercureService extends MercureService
{
    const CREATE_GUILD_CHANNEL = 'guilds/%s/channel/create';

    public function createChannel(Guild $guild, array $data)
    {
        $this->makeRequest(sprintf(self::CREATE_GUILD_CHANNEL, $guild->getId()), $data);
    }
}