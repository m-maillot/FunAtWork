<?php

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use App\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_game")
 */
class BabyfootGame
{

    const GAME_PLANNED = 0;
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
    protected $plannedDate;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * @var Player
     */
    protected $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * @var Organization
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootTournament", fetch="EAGER")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     * @var BabyfootTournament
     */
    protected $tournament;

    /**
     * BabyfootGame constructor.
     * @param int $id
     * @param int $status
     * @param BabyfootTeam $redTeam
     * @param BabyfootTeam $blueTeam
     * @param \DateTime $startedDate
     * @param \DateTime $plannedDate
     * @param \DateTime $endedDate
     * @param Player $creator
     * @param Organization $organization
     * @param BabyfootTournament|null $tournament
     */
    public function __construct($id, $status, BabyfootTeam $redTeam, BabyfootTeam $blueTeam,
                                \DateTime $startedDate, \DateTime $plannedDate, \DateTime $endedDate, Player $creator, Organization $organization,
                                BabyfootTournament $tournament)
    {
        $this->id = $id;
        $this->status = $status;
        $this->redTeam = $redTeam;
        $this->blueTeam = $blueTeam;
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
     * @return BabyfootTeam
     */
    public function getBlueTeam()
    {
        return $this->blueTeam;
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
     * @return \DateTime
     */
    public function getEndedDate()
    {
        return $this->endedDate;
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
}