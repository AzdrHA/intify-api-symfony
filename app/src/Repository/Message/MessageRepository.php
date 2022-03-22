<?php

namespace App\Repository\Message;

use App\Entity\Message\Message;
use App\Repository\DefaultRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
}