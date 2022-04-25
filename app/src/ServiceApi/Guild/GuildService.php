<?php

namespace App\ServiceApi\Guild;

use App\Entity\Guild\Guild;
use App\Exception\ApiFormErrorException;
use App\Form\Guild\CreateGuildType;
use App\Manager\Guild\GuildManager;
use App\Manager\Guild\GuildMemberManager;
use App\Service\Guild\GuildService as BaseGuildService;
use App\Service\User\UserService as BaseUserService;
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
    private BaseUserService $baseUserService;

    public function __construct(
        FormFactoryInterface $formFactory, UserService $userService, GuildManager $guildManager,
        ChannelService       $channelService, BaseGuildService $guildService, GuildMemberManager $guildMemberManager,
        BaseUserService $baseUserService
    )
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->guildManager = $guildManager;
        $this->channelService = $channelService;
        $this->guildService = $guildService;
        $this->guildMemberManager = $guildMemberManager;
        $this->baseUserService = $baseUserService;
    }

    /**
     * @param Request $request
     * @return string[]
     * @throws ApiFormErrorException|ExceptionInterface
     */
    public function createGuild(Request $request): array
    {
        $guild = new Guild();

        $closure = function () use ($guild) {
            $guild->setOwner($this->userService->getUserOrException());
            $this->channelService->initializeDefaultChannel($guild);
            $this->guildMemberManager->addMember($guild, $this->userService->getUserOrException());
            $this->guildManager->save($guild);
        };

        $this->handleForm($request, CreateGuildType::class, $guild, $closure);

        // TODO ADD SOCKET EVENT
        // TODO $this->guildService->serializeGuild($guild);
        // TODO GIVE GOOD RES
        return $this->baseUserService->serializeUser($guild->getOwner());
    }

    /**
     * @param Request $request
     * @param Guild $guild
     * @return array
     * @throws ExceptionInterface
     */
    public function getGuild(Request $request, Guild $guild): array
    {
        /*$res = $this->guildManager->getRepository()->createQueryBuilder('guild')
            ->addSelect('channels', 'channels_parent')
            ->leftJoin('guild.channels', 'channels')
            ->leftJoin('channels.parent', 'channels_parent')
            ->where('guild.id = :guild_id')
            ->groupBy('channels_parent.id')
            ->orderBy('channels.position')
            ->setParameter('guild_id', $guild->getId())
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        UtilsNormalizer::normalizeArray($res);

        return $res;*/
        return $this->guildService->serializeGuild($guild);
    }
}