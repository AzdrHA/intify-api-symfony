<?php

namespace App\Service\Guild;

use App\Entity\Guild\Guild;
use App\Service\User\UserService;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildService
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        $res['owner'] = $this->userService->serializeUser($guild->getOwner());
        return $res;
    }
}