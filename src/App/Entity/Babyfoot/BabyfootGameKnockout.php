<?php

namespace App\Entity\Babyfoot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_game_knockout")
 */
class BabyfootGameKnockout
{

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
    protected $round;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootTournament", inversedBy="games")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id", nullable=false)
     * @var BabyfootTournament
     */
    protected $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGame", fetch="EAGER")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id", nullable=false)
     * @var BabyfootGame
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGameKnockout", fetch="EAGER")
     * @ORM\JoinColumn(name="red_game_id", referencedColumnName="id")
     * @var BabyfootGameKnockout
     */
    protected $redWinnerOf;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGameKnockout", fetch="EAGER")
     * @ORM\JoinColumn(name="blue_gale_id", referencedColumnName="id")
     * @var BabyfootGameKnockout
     */
    protected $blueWinnerOf;

    /**
     * BabyfootGameKnockout constructor.
     * @param int $id
     * @param int $round
     * @param BabyfootTournament $tournament
     * @param BabyfootGame $game
     * @param BabyfootGameKnockout $redWinnerOf
     * @param BabyfootGameKnockout $blueWinnerOf
     */
    public function __construct($id, $round, BabyfootTournament $tournament, BabyfootGame $game,
                                BabyfootGameKnockout $redWinnerOf = null,
                                BabyfootGameKnockout $blueWinnerOf = null)
    {
        $this->id = $id;
        $this->round = $round;
        $this->tournament = $tournament;
        $this->game = $game;
        $this->redWinnerOf = $redWinnerOf;
        $this->blueWinnerOf = $blueWinnerOf;
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
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return BabyfootTournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @return BabyfootGame
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return BabyfootGameKnockout
     */
    public function getRedWinnerOf()
    {
        return $this->redWinnerOf;
    }

    /**
     * @return BabyfootGameKnockout
     */
    public function getBlueWinnerOf()
    {
        return $this->blueWinnerOf;
    }

}