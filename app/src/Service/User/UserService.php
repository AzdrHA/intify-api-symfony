<?php

namespace App\Service\User;

use App\Entity\User\User;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserService
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User|Exception
     */
    public function getUserOrException(): User|Exception
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof User)
            return $user;

        return new Exception("User not found", 404);
    }
}