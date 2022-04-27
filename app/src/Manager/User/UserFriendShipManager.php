<?php

namespace App\Manager\User;

use App\Entity\User\User;
use App\Manager\DefaultManager;
use App\Repository\User\UserFriendShipRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserFriendShipManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, UserFriendShipRepository $repository)
    {
        parent::__construct($em, $repository);
    }
}
