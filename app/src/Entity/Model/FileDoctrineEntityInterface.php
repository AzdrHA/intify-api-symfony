<?php

namespace App\Entity\Model;

interface FileDoctrineEntityInterface
{
    public function getDirPath($absolute = true);
    public function getFilePath($absolute = true);
    public function setClientName($clientName);
    public function getClientName();
    public function setPath($path);
    public function getPath();
    public function setFile($file);
    public function getFile();
    public function setUpdatedAt(\Datetime $updatedAt);
    public function getUpdatedAt();
    public function setCreatedAt(\DateTime $createdAt);
    public function getCreatedAt();
}
