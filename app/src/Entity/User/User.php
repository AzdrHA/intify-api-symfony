<?php

namespace App\Entity\User;

use App\Traits\TimestampableTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     * @Assert\NotNull()
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull()
     */
    private string $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull()
     */
    private string $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull()
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
     * @Assert\NotNull()
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
     * @ORM\OneToOne(targetEntity="App\Entity\User\UserStatus", inversedBy="user")
     */
    private ?UserStatus $status = null;

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
    public function getGuildsOwner(): ArrayCollection|Collection
    {
        return $this->guildsOwner;
    }

    /**
     * @param Collection $guildsOwner
     */
    public function setGuildsOwner(ArrayCollection|Collection $guildsOwner): void
    {
        $this->guildsOwner = $guildsOwner;
    }

    /**
     * @return Collection
     */
    public function getMessagesOwner(): ArrayCollection|Collection
    {
        return $this->messagesOwner;
    }

    /**
     * @param Collection $messagesOwner
     */
    public function setMessagesOwner(ArrayCollection|Collection $messagesOwner): void
    {
        $this->messagesOwner = $messagesOwner;
    }

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Guild\Guild", mappedBy="owner")
     */
    private Collection $guildsOwner;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="owner")
     */
    private Collection $messagesOwner;

    public function __construct()
    {
        $this->guildsOwner = new ArrayCollection();
        $this->messagesOwner = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
