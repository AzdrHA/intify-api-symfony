<?php

namespace App\Repository\Guild;

use App\Entity\Guild\GuildInvite;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;

class GuildInviteRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildInvite::class);
    }
}