<?php

namespace App\Entity\Message;

use App\Entity\File\File;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MessageAttachment
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
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Message\Message", inversedBy="messageAttachments", cascade={"all"})
     */
    private Message $message;

    /**
     * @var File
     *
     * @ORM\OneToOne(targetEntity="App\Entity\File\File", mappedBy="messageAttachment", cascade={"all"})
     */
    private File $file;

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
}