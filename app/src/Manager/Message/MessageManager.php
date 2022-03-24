<?php

namespace App\Manager\Message;

use App\Manager\DefaultManager;
use App\Repository\Message\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageManager extends DefaultManager
{
    public function __construct(EntityManagerInterface $em, MessageRepository $repository)
    {
        parent::__construct($em, $repository);
    }

    public function getList(int $id): array
    {
        return $this->repository->findBy(['channel' => $id]);
    }
}