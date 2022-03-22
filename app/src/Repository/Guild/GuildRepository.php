<?php

namespace App\Repository\Guild;

use App\Entity\Guild\Guild;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;

class GuildRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guild::class);
    }
}