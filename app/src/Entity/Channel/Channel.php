<?php

namespace App\Entity\Channel;

use App\Entity\File\File;
use App\Entity\Guild\Guild;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Channel
{
    const GUILD_TEXT = 0;
    const DM = 1;
    const GUILD_VOICE = 2;
    const GROUP_DM = 3;
    const GUILD_CATEGORY = 4;

    const inverse_type = [
        self::GUILD_TEXT => 'text',
        self::DM => 'dm',
        self::GUILD_VOICE => 'voice',
        self::GROUP_DM => 'direct_message',
        self::GUILD_CATEGORY => 'category'
    ];

    const CHANNEL_TYPES = [self::GUILD_TEXT, self::DM, self::GUILD_VOICE, self::GROUP_DM, self::GUILD_CATEGORY];

    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotNull()
     */
    private int $type;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    private ?int $position = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(groups={"guild"})
     * @Assert\Length(min=1, max=100, groups={"guild"})
     */
    private ?string $name = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=1, max=100, groups={"guild"})
     */
    private ?string $topic = null;

    /**
     * @var Guild|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Guild\Guild", inversedBy="channels", cascade={"all"})
     * @Assert\NotNull(groups={"guild"})
     */
    private ?Guild $guild = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="channel", cascade={"all"})
     */
    private Collection $messages;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Channel\Channel", mappedBy="parent", cascade={"all"})
     */
    private Collection $children;

    /**
     * @var Channel|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel\Channel", inversedBy="children", cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private ?Channel $parent = null;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\User", inversedBy="privateChannels", cascade={"all"})
     */
    private Collection $recipients;

    /**
     * @var File|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\File\File", inversedBy="channelIcon", cascade={"all"})
     */
    private ?File $icon = null;

    #[Pure] public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

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
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        if (!in_array($type, self::CHANNEL_TYPES)) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->type = $type;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getTopic(): ?string
    {
        return $this->topic;
    }

    /**
     * @param string|null $topic
     */
    public function setTopic(?string $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @return Guild|null
     */
    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    /**
     * @param Guild|null $guild
     */
    public function setGuild(?Guild $guild): void
    {
        $this->guild = $guild;
    }

    /**
     * @return Channel|null
     */
    public function getParent(): ?Channel
    {
        return $this->parent;
    }

    /**
     * @param Channel|null $parent
     */
    public function setParent(?Channel $parent): void
    {
        $this->parent = $parent;
    }

    public function isText(): bool
    {
        return $this->type === self::GUILD_TEXT;
    }

    public function guildChannel(): array
    {
        return array_diff(self::CHANNEL_TYPES, [self::DM]);
    }

    #[Pure] public function isGuildChannel(): bool
    {
        return in_array($this->type, $this->guildChannel());
    }
}