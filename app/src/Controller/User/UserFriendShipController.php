<?php

namespace App\Controller\User;

use App\Controller\DefaultApiController;
use App\Entity\User\User;
use App\ServiceApi\Channel\ChannelService;
use App\ServiceApi\User\UserFriendShipService;
use App\ServiceApi\User\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Rest\Route("/users/@me/friends/{user}", requirements={"user":"\d+"})
 * @ParamConverter("user", class="App\Entity\User\User", options={"id"="user"})
 */
class UserFriendShipController extends DefaultApiController
{

    /**
     * @Rest\Post("")
     * @param Request $request
     * @param User $user
     * @param UserFriendShipService $friendShipService
     * @return JsonResponse
     */
    public function friendsRequest(Request $request, User $user, UserFriendShipService $friendShipService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $friendShipService,
            'function' => 'friendsRequest',
            'args' => [$user]
        ]);
    }

    /**
     * @Rest\Post("/{status}")
     * @param Request $request
     * @param User $user
     * @param string $status
     * @param UserFriendShipService $friendShipService
     * @return JsonResponse
     */
    public function acceptOrRefuseRequest(Request $request, User $user, string $status, UserFriendShipService $friendShipService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $friendShipService,
            'function' => 'acceptOrRefuseRequest',
            'args' => [$user, $status]
        ]);
    }
}