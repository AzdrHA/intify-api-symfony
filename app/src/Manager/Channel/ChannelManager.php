<?php

namespace App\Manager\Channel;

use App\Manager\DefaultManager;
use App\Repository\Channel\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChannelManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, ChannelRepository $repository)
    {
        parent::__construct($em, $repository);
    }
}