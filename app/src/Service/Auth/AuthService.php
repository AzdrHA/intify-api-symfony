<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use App\Exception\ApiFormErrorException;
use App\Form\Auth\RegisterType;
use App\Manager\User\UserManager;
use App\Service\DefaultService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class AuthService extends DefaultService
{
    private UserManager $userManager;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        FormFactoryInterface $formFactory, UserManager $userManager, UserPasswordHasherInterface $passwordHasher
    ) {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
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
//            $this->userManager->save($user);
        };

        $this->handleForm($request, RegisterType::class, $user, $callback);

        return $this->normalizeSingle($user);
    }
}