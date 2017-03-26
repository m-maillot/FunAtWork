<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $avatar;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Player constructor.
     * @param int $id
     * @param string $avatar
     * @param string $name
     */
    public function __construct($id, $avatar, $name)
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}