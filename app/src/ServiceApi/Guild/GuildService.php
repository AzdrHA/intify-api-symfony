<?php

namespace App\ServiceApi\Guild;

use App\Entity\Guild\Guild;
use App\Exception\ApiFormErrorException;
use App\Form\Guild\CreateGuildType;
use App\Manager\Guild\GuildManager;
use App\Manager\Guild\GuildMemberManager;
use App\Service\Guild\GuildService as BaseGuildService;
use App\ServiceApi\Channel\ChannelService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildService extends DefaultService
{
    private UserService $userService;
    private GuildManager $guildManager;
    private ChannelService $channelService;
    private BaseGuildService $guildService;
    private GuildMemberManager $guildMemberManager;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserService $userService
     * @param GuildManager $guildManager
     * @param ChannelService $channelService
     * @param BaseGuildService $guildService
     * @param GuildMemberManager $guildMemberManager
     */
    public function __construct(
        FormFactoryInterface $formFactory, UserService $userService, GuildManager $guildManager,
        ChannelService $channelService, BaseGuildService $guildService, GuildMemberManager $guildMemberManager
    ) {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->guildManager = $guildManager;
        $this->channelService = $channelService;
        $this->guildService = $guildService;
        $this->guildMemberManager = $guildMemberManager;
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
            $this->channelService->initializeDefaultChannel($guild);
            $this->guildMemberManager->addMember($guild, $this->userService->getUserOrException());
            $this->guildManager->save($guild);
        };

        $this->handleForm($request, CreateGuildType::class, $guild, $closure);

        return $this->guildService->serializeGuild($guild);
    }

    /**
     * @param Request $request
     * @param Guild $guild
     * @return array
     * @throws ExceptionInterface
     */
    public function getGuild(Request $request, Guild $guild): array
    {
        return $this->guildService->serializeGuild($guild);
    }
}