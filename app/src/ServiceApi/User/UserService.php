<?php

namespace App\ServiceApi\User;

use App\Entity\User\User;
use App\Service\User\UserService as BaseUserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserService
{
    private TokenStorageInterface $tokenStorage;
    private BaseUserService $userService;

    public function __construct(TokenStorageInterface $tokenStorage, BaseUserService $userService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userService = $userService;
    }

    /**
     * @throws ExceptionInterface
     */
    public function getUserAccount(Request $request): array
    {
        return $this->userService->serializeUser($this->getUserOrException());
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