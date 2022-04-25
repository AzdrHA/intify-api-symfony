<?php

namespace App\Subscriber;

use App\Entity\Model\FileDoctrineEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileEventSubscriber implements EventSubscriber
{
    protected string $path;

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
            Events::postRemove,
            Events::preUpdate,
            Events::postUpdate,
            Events::prePersist,
            Events::postPersist
        ];
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof FileDoctrineEntity)
            $this->path = $event->getEntity()->getFilePath();
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!($entity instanceof FileDoctrineEntity))
            return;
        if ($this->path && file_exists($this->path) && is_file($this->path)) {
            unlink($this->path);
        }
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->prePersist($event);
    }

    /**
     * @throws Exception
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->postPersist($event);
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $file = $event->getEntity();
        dump($file instanceof FileDoctrineEntity);
        if ($file instanceof FileDoctrineEntity && $file->getFile() !== null) {

            $this->path = $file->getFilePath();

            if ($file->getFile() instanceof UploadedFile)
                $extension = $file->getFile()->getClientOriginalExtension();
            else
                $extension = $file->getFile()->getExtension();

            if (!$file->getPath())
                $file->setPath(sha1(uniqid(mt_rand(), true)) . '.' . $extension);
            if (method_exists($file, "setName"))
                $file->setName(sha1(uniqid(mt_rand(), true)) . '.' . $extension);
            if (method_exists($file, "setFileName"))
                $file->setFileName(sha1(uniqid(mt_rand(), true)) . '.' . $extension);
            if (method_exists($file, "setClientName") && $file->getFile() instanceof UploadedFile && $file->getFile()->getClientOriginalName())
                $file->setClientName($file->getFile()->getClientOriginalName());

            while (file_exists($file->getFilePath())) {
                $file->setPath(sha1(uniqid(mt_rand(), true)) . '.' . $extension);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $file = $event->getEntity();
        dump($file instanceof FileDoctrineEntity);
        if ($file instanceof FileDoctrineEntity && $file->getFile() !== null) {
            if ($file->getFile() instanceof UploadedFile)
                $file->setClientName($file->getFile()->getClientOriginalName());

            try {
                try {
                    if (!is_dir($file->getDirPath()))
                        mkdir($file->getDirPath(), 0776, true);
                } catch (Exception $e) {
                }
                $file->getFile()->move($file->getDirPath(), $file->getPath());
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}