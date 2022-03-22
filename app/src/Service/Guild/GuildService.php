<?php

namespace App\Service\Guild;

use App\Entity\Guild\Guild;
use App\Exception\ApiFormErrorException;
use App\Form\Guild\CreateGuildType;
use App\Manager\Guild\GuildManager;
use App\Service\DefaultService;
use App\Service\User\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildService extends DefaultService
{
    private UserService $userService;
    private GuildManager $guildManager;
    public function __construct(FormFactoryInterface $formFactory, UserService $userService, GuildManager $guildManager)
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->guildManager = $guildManager;
    }

    /**
     * @param Request $request
     * @return string[]
     * @throws ApiFormErrorException|ExceptionInterface
     */
    public function createGuild(Request $request): array
    {
        $guild = new Guild();

        $closure = function () use ($guild)
        {
            $guild->setOwner($this->userService->getUserOrException());
            $this->guildManager->save($guild);
        };

        $this->handleForm($request, CreateGuildType::class, $guild, $closure);

        return $this->normalizeSingle($guild);
    }
}