<?php

namespace App\Entity\Babyfoot;

use App\Entity\Player;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="babyfoot_goal")
 */
class BabyfootGoal
{

    const POSITION_ATTACK = 1;
    const POSITION_DEFENSE = 2;

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     * @var \DateTime
     */
    protected $goalDate;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="striker_id", referencedColumnName="id")
     * @var Player
     **/
    protected $striker;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @var int
     */
    protected $position;

    /**
     * @Doctrine\ORM\Mapping\Column(type="boolean")
     * @var bool
     */
    protected $gamelle;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="BabyfootGame", inversedBy="goals")
     * @Doctrine\ORM\Mapping\JoinColumn(name="game_id", referencedColumnName="id")
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