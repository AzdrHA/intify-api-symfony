<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Entity\User\UserFriendShip;
use App\Repository\User\UserFriendShipRepository;
use App\Repository\User\UserRepository;
use App\Service\Guild\GuildMemberService;
use App\Service\Guild\GuildService;
use App\Utils\UtilsNormalizer;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private GuildMemberService $guildMemberService;
    private UserFriendShipRepository $friendShipRepository;

    public function __construct(
        UserRepository           $userRepository, UserPasswordHasherInterface $passwordHasher, GuildMemberService $guildMemberService,
        UserFriendShipRepository $friendShipRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->guildMemberService = $guildMemberService;
        $this->friendShipRepository = $friendShipRepository;
    }

    const serializeWhitelist = [
        'id', 'email', 'firstname', 'lastname', 'username', 'enabled', 'lastLoginAt', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeUser(User $user, array $mores = []): array
    {
        $friends = $this->friendShipRepository->createQueryBuilder('f')
            ->addSelect('user, friend')
            ->leftJoin('f.user', 'user')
            ->leftJoin('f.friend', 'friend')
            ->where('user.id = :user_id OR friend.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        $serialize = array_merge(UtilsNormalizer::normalize($user, [], [], self::serializeWhitelist), $mores);
        foreach ($friends as $friend)
        {
            $ff = $friend['user']['id'] === $user->getId() ? $friend['friend'] : $friend['user'];
            unset($friend['user'], $friend['friend']);
            $serialize['friends'][] = array_merge($friend, ['friend' => $ff]);
        }

        foreach ($user->getGuildMembers() as $member) {
            $serialize['members'][] = $this->guildMemberService->serializeMember($member);
        }

        return $serialize;
    }

    public function checksUserCredentials(User $user): ?User
    {
        /** @var User $currentUser */
        $currentUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        return $currentUser ? $this->passwordHasher->isPasswordValid($currentUser, $user->getPassword()) ? $currentUser : null : null;
    }
}