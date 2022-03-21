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
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="guildsOwner", cascade={"persist"})
     * @Assert\NotNull()
     */
    private User $owner;

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
}