<?php

namespace App\Manager\Guild;

use App\Manager\DefaultManager;
use App\Repository\Guild\GuildInviteRepository;
use Doctrine\ORM\EntityManagerInterface;

class GuildInviteManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, GuildInviteRepository $repository)
    {
        parent::__construct($em, $repository);
    }
}