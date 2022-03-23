<?php

namespace App\Repository\Guild;

use App\Entity\Guild\Guild;
use App\Entity\Guild\GuildMember;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;

class GuildMemberRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildMember::class);
    }
}