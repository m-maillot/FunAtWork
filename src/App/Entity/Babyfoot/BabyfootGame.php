<?php

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use App\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="babyfoot_game")
 */
class BabyfootGame
{

    const GAME_PLANNED = 0;
    const GAME_STARTED = 1;
    const GAME_OVER = 2;
    const GAME_CANCELED = 3;

    const GAME_MODE_SCORE = 1;
    const GAME_MODE_TIME = 2;

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @var int
     */
    protected $status;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="red_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    protected $redTeam;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="blue_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    protected $blueTeam;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @var int
     */
    protected $mode;

    /**
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @var int
     */
    protected $modeLimitValue;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="BabyfootGoal", mappedBy="game", fetch="EAGER")
     * @var ArrayCollection
     */
    protected $goals;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $plannedDate;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $startedDate;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $endedDate;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="creator_id", referencedColumnName="id")
     * @var Player
     */
    protected $creator;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="organization_id", referencedColumnName="id")
     * @var Organization
     */
    protected $organization;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootTournament", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="tournament_id", referencedColumnName="id")
     * @var BabyfootTournament
     */
    protected $tournament;

    /**
     * BabyfootGame constructor.
     * @param int $id
     * @param int $status
     * @param BabyfootTeam $redTeam
     * @param BabyfootTeam $blueTeam
     * @param int $mode
     * @param int $modeLimitValue
     * @param \DateTime $startedDate
     * @param \DateTime $plannedDate
     * @param \DateTime $endedDate
     * @param Player $creator
     * @param Organization $organization
     * @param BabyfootTournament|null $tournament
     */
    public function __construct($id, $status, BabyfootTeam $redTeam = null, BabyfootTeam $blueTeam = null,
                                $mode, $modeLimitValue,
                                \DateTime $startedDate = null, \DateTime $plannedDate = null, \DateTime $endedDate = null, Player $creator, Organization $organization,
                                BabyfootTournament $tournament = null)
    {
        $this->id = $id;
        $this->status = $status;
        $this->redTeam = $redTeam;
        $this->blueTeam = $blueTeam;
        $this->mode = $mode;
        $this->modeLimitValue = $modeLimitValue;
        $this->goals = new ArrayCollection();
        $this->startedDate = $startedDate;
        $this->plannedDate = $plannedDate;
        $this->endedDate = $endedDate;
        $this->creator = $creator;
        $this->organization = $organization;
        $this->tournament = $tournament;
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
     * @param BabyfootTeam $redTeam
     */
    public function setRedTeam($redTeam)
    {
        $this->redTeam = $redTeam;
    }

    /**
     * @return BabyfootTeam
     */
    public function getBlueTeam()
    {
        return $this->blueTeam;
    }

    /**
     * @param BabyfootTeam $blueTeam
     */
    public function setBlueTeam($blueTeam)
    {
        $this->blueTeam = $blueTeam;
    }

    /**
     * @return ArrayCollection|BabyfootGoal[]
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
     * @param \DateTime $startedDate
     */
    public function setStartedDate($startedDate)
    {
        $this->startedDate = $startedDate;
    }

    /**
     * @return \DateTime
     */
    public function getPlannedDate()
    {
        return $this->plannedDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndedDate()
    {
        return $this->endedDate;
    }

    /**
     * @param \DateTime $endedDate
     */
    public function setEndedDate($endedDate)
    {
        $this->endedDate = $endedDate;
    }

    /**
     * @return Player
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return BabyfootTournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getModeLimitValue()
    {
        return $this->modeLimitValue;
    }
}