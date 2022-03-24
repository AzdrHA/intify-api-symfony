<?php

namespace App\Repository\Channel;

use App\Entity\Channel\Channel;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChannelRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Channel::class);
    }
}