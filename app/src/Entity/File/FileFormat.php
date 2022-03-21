<?php

namespace App\Entity\File;

use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FileFormat
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $mimetype;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\File\File", mappedBy="format")
     */
    private array $files;

    /**
     * @return string
     */
    public function getMimetype(): string
    {
        return $this->mimetype;
    }

    /**
     * @param string $mimetype
     */
    public function setMimetype(string $mimetype): void
    {
        $this->mimetype = $mimetype;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }
}