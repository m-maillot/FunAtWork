<?php

namespace App\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule")
 */
class Rule
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
    private $content;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     * @var \DateTime
     */
    private $lastUpdateDate;

    /**
     * Rule constructor.
     * @param string $content
     * @param \DateTime $lastUpdateDate
     */
    public function __construct($content, \DateTime $lastUpdateDate)
    {
        $this->content = $content;
        $this->lastUpdateDate = $lastUpdateDate;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }
}