<?php

namespace App\Manager\Guild;

use App\Entity\Channel\Channel;
use App\Entity\Guild\Guild;
use App\Entity\Guild\GuildMember;
use App\Entity\User\User;
use App\Manager\DefaultManager;
use App\Repository\Guild\GuildRepository;
use Doctrine\ORM\EntityManagerInterface;

class GuildMemberManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, GuildRepository $repository)
    {
        parent::__construct($em, $repository);
    }

    public function addMember(Guild $guild, User $user): GuildMember
    {
        $guildMember = new GuildMember();
        $guildMember->setJoinAt(new \DateTime());
        $guildMember->setName($user->getUsername());
        $guildMember->setUser($user);
        $guildMember->setGuild($guild);
        $this->save($guildMember);

        return $guildMember;
    }
}