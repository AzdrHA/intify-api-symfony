<?php

namespace App\Service\Guild;

use App\Entity\Guild\Guild;
use App\Entity\Guild\GuildInvite;
use App\Service\Channel\ChannelService;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildInviteService
{
    const serializeWhitelist = [
        'id', 'code', 'guild', 'channel', 'inviter', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeGuildInvite(GuildInvite $invite): array
    {
        return UtilsNormalizer::normalize($invite, [], [], self::serializeWhitelist);
    }
}