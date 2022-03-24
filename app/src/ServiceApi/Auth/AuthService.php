<?php

namespace App\ServiceApi\Auth;

use App\Entity\User\User;
use App\Exception\ApiFormErrorException;
use App\Form\Auth\LoginType;
use App\Form\Auth\RegisterType;
use App\Manager\User\UserManager;
use App\Service\User\UserService as BaseUserService;
use App\ServiceApi\DefaultService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class AuthService extends DefaultService
{
    private UserManager $userManager;
    private UserPasswordHasherInterface $passwordHasher;
    private BaseUserService $userService;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param BaseUserService $userService
     */
    public function __construct(
        FormFactoryInterface $formFactory, UserManager $userManager, UserPasswordHasherInterface $passwordHasher,
        BaseUserService $userService
    ) {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
        $this->userService = $userService;
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
     */
    public function login(Request $request): array
    {
        $user = new User();

        $callback = function ()
        {

        };

        $this->handleForm($request, LoginType::class, $user, $callback);

        return $this->userService->serializeUser($user);
    }
}