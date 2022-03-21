<?php

namespace App\Controller\Auth;

use App\Controller\DefaultApiController;
use App\Service\Auth\AuthService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/auth")
 */
class AuthController extends DefaultApiController
{
    /**
     * @Rest\Post("/register")
     * @param Request $request
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function register(Request $request, AuthService $authService): JsonResponse
    {
        return $this->handleRequest($request, [
            'service' => $authService,
            'function' => 'register',
            'args' => []
        ]);
    }
}