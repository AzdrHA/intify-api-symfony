<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserService
{
    const serializeWhitelist = [
        'id', 'email', 'firstname', 'lastname', 'username', 'enabled', 'lastLoginAt', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeUser(User $user): array
    {
        return UtilsNormalizer::normalize($user, [], [], self::serializeWhitelist);
    }
}