<?php

namespace App\Entity\Channel;

use App\Entity\Guild\Guild;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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

    const type_list = [self::GUILD_TEXT, self::DM, self::GUILD_VOICE, self::GROUP_DM, self::GUILD_CATEGORY];

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
     * @Assert\NotNull(groups={"guild"})
     */
    private ?int $position = null;

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
     * @Assert\NotNull(groups={"guild"})
     * @Assert\Length(min=1, max=100, groups={"guild"})
     */
    private ?string $topic = null;

    /**
     * @var Guild|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Guild\Guild", inversedBy="channels", cascade={"persist"})
     * @Assert\NotNull(groups={"guild"})
     */
    private ?Guild $guild = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="channel")
     */
    private Collection $messages;

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
        if (!in_array($type, self::type_list)) {
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
}