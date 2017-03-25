<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_goal")
 */
class BabyfootGoal
{

    const POSITION_ATTACK = 1;
    const POSITION_DEFENSE = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $goalDate;

    /**
     * @ORM\ManyToOne(targetEntity="Player", fetch="EAGER")
     * @ORM\JoinColumn(name="striker_id", referencedColumnName="id")
     * @var Player
     **/
    private $striker;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $gamelle;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootGame", inversedBy="games")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * Goal constructor.
     * @param int $id
     * @param \DateTime $goalDate
     * @param Player $striker
     * @param int $position
     * @param bool $gamelle
     * @param $game
     */
    public function __construct($id, \DateTime $goalDate, Player $striker, $position, $gamelle, $game)
    {
        $this->id = $id;
        $this->goalDate = $goalDate;
        $this->striker = $striker;
        $this->position = $position;
        $this->gamelle = $gamelle;
        $this->game = $game;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getGoalDate()
    {
        return $this->goalDate;
    }

    /**
     * @return Player
     */
    public function getStriker()
    {
        return $this->striker;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isGamelle()
    {
        return $this->gamelle;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
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