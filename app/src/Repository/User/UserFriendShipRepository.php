<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Entity\User\UserFriendShip;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserFriendShipRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFriendShip::class);
    }
}
