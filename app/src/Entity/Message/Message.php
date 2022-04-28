<?php

namespace App\Entity\Message;

use App\Entity\Channel\Channel;
use App\Entity\User\User;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Message
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
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="messagesOwner", cascade={"all"})
     */
    private User $owner;

    /**
     * @var Channel
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel\Channel", inversedBy="messages", cascade={"all"})
     */
    private Channel $channel;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message\MessageAttachment", mappedBy="message", cascade={"all"})
     */
    private Collection $messageAttachments;

    public function __construct()
    {
        $this->messageAttachments = new ArrayCollection();
    }

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
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     */
    public function setChannel(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Collection
     */
    public function getMessageAttachments(): Collection
    {
        return $this->messageAttachments;
    }

    /**
     * @param Collection $messageAttachments
     */
    public function setMessageAttachments(Collection $messageAttachments): void
    {
        $this->messageAttachments = $messageAttachments;
    }

    public function addMessageAttachments(MessageAttachment $attachment): self
    {
        if (!$this->messageAttachments->contains($attachment)) {
            $this->messageAttachments[] = $attachment;
            $attachment->setMessage($this);
        }

        return $this;
    }
}