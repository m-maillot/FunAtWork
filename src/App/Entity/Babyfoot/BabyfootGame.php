<?php

namespace App\Entity\Babyfoot;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_game")
 */
class BabyfootGame
{

    const GAME_STARTED = 1;
    const GAME_OVER = 2;
    const GAME_CANCELED = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @ORM\JoinColumn(name="red_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    protected $redTeam;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @ORM\JoinColumn(name="blue_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    protected $blueTeam;

    /**
     * @ORM\OneToMany(targetEntity="BabyfootGoal", mappedBy="game", fetch="EAGER")
     * @var ArrayCollection
     */
    protected $goals;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $startedDate;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $endedDate;

    /**
     * BabyfootGame constructor.
     * @param int $id
     * @param int $status
     * @param BabyfootTeam $redTeam
     * @param BabyfootTeam $blueTeam
     * @param \DateTime $startedDate
     * @param \DateTime $endedDate
     */
    public function __construct($id, $status, BabyfootTeam $redTeam, BabyfootTeam $blueTeam, \DateTime $startedDate, \DateTime $endedDate)
    {
        $this->id = $id;
        $this->status = $status;
        $this->redTeam = $redTeam;
        $this->blueTeam = $blueTeam;
        $this->goals = new ArrayCollection();
        $this->startedDate = $startedDate;
        $this->endedDate = $endedDate;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return BabyfootTeam
     */
    public function getRedTeam()
    {
        return $this->redTeam;
    }

    /**
     * @return BabyfootTeam
     */
    public function getBlueTeam()
    {
        return $this->blueTeam;
    }

    /**
     * @return ArrayCollection
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @param BabyfootGoal[] $goals
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;
    }

    /**
     * @return \DateTime
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndedDate()
    {
        return $this->endedDate;
    }
}