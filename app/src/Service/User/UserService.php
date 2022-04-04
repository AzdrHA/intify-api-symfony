<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Utils\UtilsNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    const serializeWhitelist = [
        'id', 'email', 'firstname', 'lastname', 'username', 'enabled', 'lastLoginAt', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeUser(User $user, array $mores = []): array
    {
        return array_merge(UtilsNormalizer::normalize($user, [], [], self::serializeWhitelist), $mores);
    }

    public function checksUserCredentials(User $user): ?User
    {
        /** @var User $currentUser */
        $currentUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        return $currentUser ? $this->passwordHasher->isPasswordValid($currentUser, $user->getPassword()) ? $currentUser : null : null;
    }
}