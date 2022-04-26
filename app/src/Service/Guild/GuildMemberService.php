<?php

namespace App\Service\Guild;

use App\Entity\Guild\GuildMember;
use App\Service\User\UserService;
use App\Utils\UtilsNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GuildMemberService
{
    private GuildService $guildService;
    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    const serializeWhitelist = [
        'id', 'name', 'jointAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeMember(GuildMember $member): array
    {
        $res = UtilsNormalizer::normalize($member, [], [], self::serializeWhitelist);
        $res['guild'] = $this->guildService->serializeGuild($member->getGuild());
        return $res;
    }

    /**
     * @throws ExceptionInterface
     */
    public function serializeMembersCollection(ArrayCollection|Collection $guildMembers): array
    {
        $res = [];
        foreach ($guildMembers as $member)
        {
            $res[] = $this->serializeMember($member);
        }

        return $res;
    }
}