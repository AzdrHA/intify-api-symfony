<?php

namespace App\Entity\User;

use App\Traits\TimestampableTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     * @Assert\NotNull(groups={"register", "login"})
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(groups={"register"})
     */
    private string $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(groups={"register"})
     */
    private string $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(groups={"register"})
     */
    private string $username;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(groups={"register", "login"})
     */
    private string $password;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $enabled = false;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $lastLoginAt = null;

    /**
     * @var UserStatus|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User\UserStatus", inversedBy="user", cascade={"all"})
     */
    private ?UserStatus $status = null;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Channel\Channel", mappedBy="recipients", cascade={"all"})
     */
    private Collection $privateChannels;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Guild\GuildMember", mappedBy="user", cascade={"all"})
     */
    private Collection $guildMembers;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Guild\Guild", mappedBy="owner")
     */
    private Collection $guildOwned;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="owner")
     */
    private Collection $messagesOwner;

    #[Pure] public function __construct()
    {
        $this->guildOwned = new ArrayCollection();
        $this->messagesOwner = new ArrayCollection();
        $this->privateChannels = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return DateTime|null
     */
    public function getLastLoginAt(): ?DateTime
    {
        return $this->lastLoginAt;
    }

    /**
     * @param DateTime|null $lastLoginAt
     */
    public function setLastLoginAt(?DateTime $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    /**
     * @return UserStatus|null
     */
    public function getStatus(): ?UserStatus
    {
        return $this->status;
    }

    /**
     * @param UserStatus|null $status
     */
    public function setStatus(?UserStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Collection
     */
    public function getPrivateChannels(): Collection
    {
        return $this->privateChannels;
    }

    /**
     * @param Collection $privateChannels
     */
    public function setPrivateChannels(Collection $privateChannels): void
    {
        $this->privateChannels = $privateChannels;
    }

    /**
     * @return Collection
     */
    public function getGuildMembers(): Collection
    {
        return $this->guildMembers;
    }

    /**
     * @param Collection $guildMembers
     */
    public function setGuildMembers(Collection $guildMembers): void
    {
        $this->guildMembers = $guildMembers;
    }

    /**
     * @return Collection
     */
    public function getGuildOwned(): Collection
    {
        return $this->guildOwned;
    }

    /**
     * @param Collection $guildOwned
     */
    public function setGuildOwned(Collection $guildOwned): void
    {
        $this->guildOwned = $guildOwned;
    }

    /**
     * @return Collection
     */
    public function getMessagesOwner(): Collection
    {
        return $this->messagesOwner;
    }

    /**
     * @param Collection $messagesOwner
     */
    public function setMessagesOwner(Collection $messagesOwner): void
    {
        $this->messagesOwner = $messagesOwner;
    }
}
