<?php

namespace App\Entity\Message;

use App\Entity\File\File;
use App\Entity\Model\FileDoctrineEntity;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MessageAttachment extends FileDoctrineEntity
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
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Message\Message", inversedBy="messageAttachments", cascade={"all"})
     */
    private Message $message;

    /**
     * @param Message $message
     */
    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }
}