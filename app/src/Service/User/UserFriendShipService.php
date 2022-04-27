<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Entity\User\UserFriendShip;
use App\Repository\User\UserFriendShipRepository;
use App\Utils\UtilsNormalizer;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserFriendShipService
{
    private UserFriendShipRepository $friendShipRepository;
    public function __construct(UserFriendShipRepository $friendShipRepository)
    {
        $this->friendShipRepository = $friendShipRepository;
    }

    const serializeWhitelist = [
        'id', 'status', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws NonUniqueResultException
     */
    public function hasCommonRequest(User $from, User $to): UserFriendShip|null
    {
        return $this->friendShipRepository->createQueryBuilder('f')
            ->leftJoin('f.user', 'user')
            ->leftJoin('f.friend', 'friend')
            ->where('user.id = :from AND friend.id = :to')
            ->orWhere('user.id = :to AND friend.id = :from')
            ->setParameters([
                'from' => $from,
                'to' => $to
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ExceptionInterface
     */
    public function serializeFriend(UserFriendShip $friendShip): array
    {
        return UtilsNormalizer::normalize($friendShip, [], [], self::serializeWhitelist);
    }
}