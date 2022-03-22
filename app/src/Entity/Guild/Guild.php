<?php

namespace App\Entity\Guild;

use App\Entity\File\File;
use App\Entity\User\User;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Guild
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

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
     * @ORM\OneToOne(targetEntity="App\Entity\File\File", inversedBy="guildIcon")
     */
    private ?File $icon = null;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="guildsOwner", cascade={"persist"})
     */
    private ?User $owner = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Channel\Channel", mappedBy="guild")
     */
    private Collection $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
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
}