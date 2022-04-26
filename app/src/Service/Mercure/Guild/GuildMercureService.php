<?php

namespace App\Service\Mercure\Guild;

use App\Entity\User\User;
use App\Service\Mercure\MercureService;

class GuildMercureService extends MercureService
{
    const CREATE_GUILD = 'guilds/create/users/%s';

    public function createGuild(User $user, array $data)
    {
        $this->makeRequest(sprintf(self::CREATE_GUILD, $user->getId()), $data);
    }
}