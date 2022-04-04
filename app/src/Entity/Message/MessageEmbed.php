<?php

namespace App\Entity\Message;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MessageEmbed
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

}