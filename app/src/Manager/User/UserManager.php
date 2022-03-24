<?php

namespace App\Manager\User;

use App\Entity\User\User;
use App\Manager\DefaultManager;
use App\Repository\User\UserRepository;
use App\Utils\UtilsNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        parent::__construct($em, $repository);
    }
}