<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="group")
 */
class Group
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
    private $icon;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Group constructor.
     * @param int $id
     * @param string $icon
     * @param string $name
     */
    public function __construct($id, $icon, $name)
    {
        $this->id = $id;
        $this->icon = $icon;
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
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}