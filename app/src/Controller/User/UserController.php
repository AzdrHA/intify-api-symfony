<?php

namespace App\Controller\User;

use App\Controller\DefaultApiController;
use App\ServiceApi\User\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Rest\Route("/users")
 */
class UserController extends DefaultApiController
{
    /**
     * @Rest\Get("/account")
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getUserAccount(Request $request, UserService $userService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $userService,
            'function' => 'getUserAccount',
            'args' => []
        ]);
    }
}