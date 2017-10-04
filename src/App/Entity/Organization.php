<?php

namespace App\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="organization")
 */
class Organization
{

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $icon;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
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