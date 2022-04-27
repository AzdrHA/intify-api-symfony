<?php

namespace App\ServiceApi\User;

use App\Entity\User\User;
use App\Entity\User\UserFriendShip;
use App\Exception\ApiException;
use App\Manager\User\UserFriendShipManager;
use App\Service\User\UserFriendShipService as BaseUserFriendShipService;
use App\ServiceApi\DefaultService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UserFriendShipService extends DefaultService
{
    private UserService $userService;
    private UserFriendShipManager $friendShipManager;
    private BaseUserFriendShipService $friendShipService;

    public function __construct(
        UserService $userService, UserFriendShipManager $friendShipManager, BaseUserFriendShipService $friendShipService
    )
    {
        $this->userService = $userService;
        $this->friendShipManager = $friendShipManager;
        $this->friendShipService = $friendShipService;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array
     * @throws ExceptionInterface|ApiException|NonUniqueResultException
     */
    public function friendsRequest(Request $request, User $user): array
    {
        $me = $this->userService->getUserOrException();

        if ($me->getId() === $user->getId())
            throw new ApiException('Impossible to add you as a friend');

        $checksFriendShip = $this->friendShipService->hasCommonRequest($this->userService->getUserOrException(), $user);
        if ($checksFriendShip)
            throw new ApiException("These 2 users have already pending friendship");

        /** @var UserFriendShip $friend */
        $friend = $this->friendShipManager->create();
        $friend->setUser($me);
        $friend->setStatus($friend::PENDING);
        $friend->setFriend($user);
        $this->friendShipManager->save($friend);

        return $this->friendShipService->serializeFriend($friend);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param string $status
     * @return array
     * @throws ApiException
     * @throws ExceptionInterface|NonUniqueResultException
     */
    public function acceptOrRefuseRequest(Request $request, User $user, string $status): array
    {
        if (!in_array($status, UserFriendShip::STATUS_LIST))
            throw new ApiException("status invalid");

        if ($status !== UserFriendShip::PENDING)
            throw new ApiException("request already modify");

        $checksFriendShip = $this->friendShipService->hasCommonRequest($this->userService->getUserOrException(), $user);

        if (!$checksFriendShip)
            throw new ApiException("These 2 users have no pending friendship");

        if ($status === UserFriendShip::ACCEPT) {
            $checksFriendShip->setStatus(UserFriendShip::ACCEPT);
            $this->friendShipManager->save($checksFriendShip);

            return $this->friendShipService->serializeFriend($checksFriendShip);
        } else {
            $this->friendShipManager->remove($checksFriendShip);
            return ['ok'];
        }
    }
}