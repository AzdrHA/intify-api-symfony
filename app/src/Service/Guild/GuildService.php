<?php

namespace App\Service\Guild;

use App\Entity\Guild\Guild;
use App\Service\Channel\ChannelService;
use App\Service\User\UserService;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildService
{
    private ChannelService $channelService;
    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    const serializeWhitelist = [
        'id', 'icon', 'owner', 'name', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeGuild(Guild $guild): array
    {
        $res = UtilsNormalizer::normalize($guild, [], [], self::serializeWhitelist);
        $res['channels'] = $this->channelService->serializeChannel($guild->getChannels());
        return $res;
    }
}