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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

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
}