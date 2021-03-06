<?php

namespace App\Entity\Guild;

use App\Entity\User\User;
use App\Traits\TimestampableTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class GuildMember
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
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=100)
     */
    private string $name;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $joinAt = null;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="guildMembers", cascade={"all"})
     */
    private User $user;

    /**
     * @var Guild
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Guild\Guild", inversedBy="guildMembers", cascade={"all"})
     */
    private Guild $guild;

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
     * @return DateTime|null
     */
    public function getJoinAt(): ?DateTime
    {
        return $this->joinAt;
    }

    /**
     * @param DateTime|null $joinAt
     */
    public function setJoinAt(?DateTime $joinAt): void
    {
        $this->joinAt = $joinAt;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
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
}