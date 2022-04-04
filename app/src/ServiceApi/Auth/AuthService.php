<?php

namespace App\ServiceApi\Auth;

use App\Entity\User\User;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Auth\LoginType;
use App\Form\Auth\RegisterType;
use App\Manager\User\UserManager;
use App\Service\Auth\AuthService as BaseAuthService;
use App\Service\User\UserService as BaseUserService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class AuthService extends DefaultService
{
    private $user;
    private UserManager $userManager;
    private UserPasswordHasherInterface $passwordHasher;
    private BaseUserService $userService;
    private BaseAuthService $authService;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param BaseUserService $userService
     * @param BaseAuthService $authService
     */
    public function __construct(
        FormFactoryInterface $formFactory, UserManager $userManager, UserPasswordHasherInterface $passwordHasher,
        BaseUserService $userService, BaseAuthService $authService
    ) {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return array
     * @throws ApiFormErrorException
     * @throws ExceptionInterface
     */
    public function register(Request $request): array
    {
        $user = new User();

        $callback = function () use ($user)
        {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->userManager->save($user);
        };

        $this->handleForm($request, RegisterType::class, $user, $callback);

        return $this->userService->serializeUser($user);
    }

    /**
     * @throws ExceptionInterface
     * @throws ApiFormErrorException
     * @throws ApiException
     */
    public function login(Request $request): array
    {
        $user = new User();

        $callback = function () use ($user)
        {
            $this->user = $this->userService->checksUserCredentials($user);
            if (!$this->user)
                throw new ApiException('Invalid credential', 400);
        };

        $this->handleForm($request, LoginType::class, $user, $callback, [
            'validation_groups' => ['login']
        ]);

        return $this->userService->serializeUser($this->user, ['token' => $this->authService->createToken($this->user)]);
    }
}