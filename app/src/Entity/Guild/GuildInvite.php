<?php

namespace App\Entity\Guild;

use App\Entity\Channel\Channel;
use App\Entity\User\User;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class GuildInvite
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
    private string $code;

    /**
     * @var Guild
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Guild\Guild", inversedBy="invites", cascade={"all"})
     * @Assert\NotNull()
     */
    private Guild $guild;

    /**
     * @var Channel
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel\Channel", inversedBy="invites", cascade={"all"})
     */
    private Channel $channel;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="invites", cascade={"all"})
     */
    private User $inviter;

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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return Guild
     */
    public function getGuild(): Guild
    {
        return $this->guild;
    }

    /**
     * @param Guild $guild
     */
    public function setGuild(Guild $guild): void
    {
        $this->guild = $guild;
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
    public function getInviter(): User
    {
        return $this->inviter;
    }

    /**
     * @param User $inviter
     */
    public function setInviter(User $inviter): void
    {
        $this->inviter = $inviter;
    }
}