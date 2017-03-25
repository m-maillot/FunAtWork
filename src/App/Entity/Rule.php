<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rule")
 */
class Rule
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
    private $content;

    /**
     * @ORM\Column(type="datetime")
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

    public function expose()
    {
        $var = get_object_vars($this);
        foreach ($var as &$value) {
            if (is_object($value) && method_exists($value, 'expose')) {
                $value = $value->expose();
            }
        }
        return $var;
    }
}