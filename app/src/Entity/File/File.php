<?php

namespace App\Entity\File;

use App\Entity\Guild\Guild;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class File
{
    use TimestampableTrait;

    /**
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
    private string $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $path;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private int $size;

    /**
     * @var Guild
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Guild\Guild", mappedBy="icon")
     */
    private Guild $guildIcon;

    /**
     * @var FileFormat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\File\FileFormat", inversedBy="files", cascade={"persist"})
     */
    private FileFormat $format;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return FileFormat
     */
    public function getFormat(): FileFormat
    {
        return $this->format;
    }

    /**
     * @param FileFormat $format
     */
    public function setFormat(FileFormat $format): void
    {
        $this->format = $format;
    }
}