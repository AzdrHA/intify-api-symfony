<?php

namespace App\Manager;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DefaultManager
{
    protected EntityManagerInterface $em;
    protected EntityRepository $repository;
    protected string $className = "";

    /**
     * @param EntityManagerInterface $em
     * @param EntityRepository $repository
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repository)
    {
        $this->em = $em;
        $this->className = $repository->getClassName();
        $this->repository = $repository;
    }

    /**
     * @param object $entity
     * @param bool $flush
     * @return void
     */
    public function save(object $entity, bool $flush = true): void
    {
        if (method_exists($entity, 'getCreatedAt'))
            if (!$entity->getCreatedAt() && method_exists($entity, 'setCreatedAt'))
                $entity->setCreatedAt(new DateTime());


        if (method_exists($entity, "setUpdatedAt"))
            $entity->setUpdatedAt(new DateTime());

        $this->em->persist($entity);
        if ($flush)
            $this->em->flush();
    }

    /**
     * @param User $entity
     * @param bool $flush
     * @return void
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->em->remove($entity);
        if ($flush)
            $this->em->flush();
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->em->flush();
    }
}