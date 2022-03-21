<?php

namespace App\Manager\User;

use App\Manager\DefaultManager;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        parent::__construct($em, $repository);
    }
}