<?php

namespace App\Entity\Guild;

use App\Entity\Channel\Channel;
use App\Entity\File\File;
use App\Entity\User\User;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Guild
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
     * @var File|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\File\File", inversedBy="guildIcon", cascade={"all"})
     */
    private ?File $icon = null;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="guildOwned", cascade={"all"})
     */
    private ?User $owner = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Channel\Channel", mappedBy="guild", cascade={"all"})
     */
    private Collection $channels;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Guild\GuildMember", mappedBy="guild", cascade={"all"})
     */
    private Collection $guildMembers;

    #[Pure] public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->guildMembers = new ArrayCollection();
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
     * @return File|null
     */
    public function getIcon(): ?File
    {
        return $this->icon;
    }

    /**
     * @param File|null $icon
     */
    public function setIcon(?File $icon): void
    {
        $this->icon = $icon;
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
     * @return ArrayCollection|Collection
     */
    public function getChannels(): ArrayCollection|Collection
    {
        return $this->channels;
    }

    /**
     * @param ArrayCollection|Collection $channels
     */
    public function setChannels(ArrayCollection|Collection $channels): void
    {
        $this->channels = $channels;
    }

    public function addChannel(Channel $channel): self
    {
        if (!$this->channels->contains($channel)) {
            $this->channels[] = $channel;
            $channel->setGuild($this);
        }

        return $this;
    }
}