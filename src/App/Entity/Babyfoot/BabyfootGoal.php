<?php

namespace App\Entity\Babyfoot;

use App\Entity\Player;
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
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $goalDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @ORM\JoinColumn(name="striker_id", referencedColumnName="id")
     * @var Player
     **/
    protected $striker;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $position;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $gamelle;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootGame", inversedBy="goals")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    protected $game;

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
}