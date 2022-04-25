<?php

namespace App\Entity\File;

use App\Entity\Channel\Channel;
use App\Entity\Guild\Guild;
use App\Entity\Message\MessageAttachment;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class File
{
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="bigint", unique=true, nullable=false)
     * @ORM\CustomIdGenerator(class="App\Doctrine\SnowflakeGenerator")
     */
    private string $id;

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
     * @ORM\OneToOne(targetEntity="App\Entity\Guild\Guild", mappedBy="icon", cascade={"all"})
     */
    private Guild $guildIcon;

    /**
     * @var Channel
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Channel\Channel", mappedBy="icon", cascade={"all"})
     */
    private Channel $channelIcon;

    /**
     * @var MessageAttachment
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Message\MessageAttachment", mappedBy="file", cascade={"all"})
     */
    private MessageAttachment $messageAttachment;

    /**
     * @return MessageAttachment
     */
    public function getMessageAttachment(): MessageAttachment
    {
        return $this->messageAttachment;
    }

    /**
     * @param MessageAttachment $messageAttachment
     */
    public function setMessageAttachment(MessageAttachment $messageAttachment): void
    {
        $this->messageAttachment = $messageAttachment;
    }

    /**
     * @var FileFormat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\File\FileFormat", inversedBy="files", cascade={"all"})
     */
    private FileFormat $format;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
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