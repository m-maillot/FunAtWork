<?php

namespace App\Entity\Babyfoot;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="babyfoot_game_knockout")
 */
class BabyfootGameKnockout
{

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
    protected $round;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootTournament", inversedBy="games")
     * @Doctrine\ORM\Mapping\JoinColumn(name="tournament_id", referencedColumnName="id", nullable=false)
     * @var BabyfootTournament
     */
    protected $tournament;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGame", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="match_id", referencedColumnName="id", nullable=false)
     * @var BabyfootGame
     */
    protected $game;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGameKnockout", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="red_game_id", referencedColumnName="id")
     * @var BabyfootGameKnockout
     */
    protected $redWinnerOf;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGameKnockout", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="blue_gale_id", referencedColumnName="id")
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