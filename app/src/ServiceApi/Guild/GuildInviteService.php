<?php

namespace App\ServiceApi\Guild;

use App\Entity\Guild\GuildInvite;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Guild\Invite\CreateGuildInviteType;
use App\Manager\Guild\GuildInviteManager;
use App\Manager\Guild\GuildManager;
use App\Manager\Guild\GuildMemberManager;
use App\Service\Guild\GuildInviteService as BaseGuildInviteService;
use App\Service\Guild\GuildMemberService;
use App\Service\Mercure\Guild\GuildMercureService;
use App\ServiceApi\User\UserService;
use App\ServiceApi\DefaultService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildInviteService extends DefaultService
{
    private BaseGuildInviteService $guildInviteService;
    private UserService $userService;
    private GuildInviteManager $guildInviteManager;
    private GuildMemberManager $guildMemberManager;
    private GuildManager $guildManager;
    private GuildMercureService $guildMercureService;
    private GuildMemberService $guildMemberService;

    public function __construct(
        FormFactoryInterface $formFactory, BaseGuildInviteService $guildInviteService, UserService $userService,
        GuildInviteManager $guildInviteManager, GuildMemberManager $guildMemberManager, GuildManager $guildManager,
        GuildMercureService  $guildMercureService, GuildMemberService $guildMemberService
    )
    {
        $this->formFactory = $formFactory;
        $this->guildInviteService = $guildInviteService;
        $this->userService = $userService;
        $this->guildInviteManager = $guildInviteManager;
        $this->guildMemberManager = $guildMemberManager;
        $this->guildManager = $guildManager;
        $this->guildMercureService = $guildMercureService;
        $this->guildMemberService = $guildMemberService;
    }

    /**
     * @throws ApiFormErrorException
     * @throws ExceptionInterface
     */
    public function createGuildInvite(Request $request): array
    {
        $invite = new GuildInvite();

        $callback = function () use ($invite)
        {
            $invite->setCode(uniqid());
            $invite->setInviter($this->userService->getUserOrException());
            $this->guildInviteManager->save($invite);
        };

        $this->handleForm($request, CreateGuildInviteType::class, $invite, $callback);
        return $this->guildInviteService->serializeGuildInvite($invite);
    }

    /**
     * @throws ApiException
     * @throws ExceptionInterface
     */
    public function addGuildMember(Request $request, string $code): array
    {
        /** @var GuildInvite $invite */
        $invite = $this->guildInviteManager->getRepository()->findOneBy(['code' => $code]);
        if (!$invite)
            throw new ApiException('the code is not valid');

        $this->guildMemberManager->addMember($invite->getGuild(), $this->userService->getUserOrException());
        $this->guildManager->save($invite->getGuild());
        $this->guildMercureService->createGuild($this->userService->getUserOrException(), $this->guildMemberService->serializeMembersCollection($this->userService->getUserOrException()->getGuildMembers()));

        return $this->guildInviteService->serializeGuildInvite($invite);
    }
}