<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 4:53 PM
 */

namespace App\Entity\Babyfoot;


class BabyfootGameKnockout
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootTournament", fetch="EAGER")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     * @var BabyfootTournament
     */
    protected $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGame", fetch="EAGER")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * @var BabyfootGame
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGame", fetch="EAGER")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * @var BabyfootGame
     */
    protected $redWinnerOf;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Babyfoot\BabyfootGame", fetch="EAGER")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * @var BabyfootGame
     */
    protected $blueWinnerOf;

    /**
     * BabyfootGameKnockout constructor.
     * @param $tournament
     * @param BabyfootGame $game
     * @param BabyfootGame $redWinnerOf
     * @param BabyfootGame $blueWinnerOf
     */
    public function __construct($tournament, BabyfootGame $game, BabyfootGame $redWinnerOf, BabyfootGame $blueWinnerOf)
    {
        $this->tournament = $tournament;
        $this->game = $game;
        $this->redWinnerOf = $redWinnerOf;
        $this->blueWinnerOf = $blueWinnerOf;
    }

    /**
     * @return mixed
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
     * @return BabyfootGame
     */
    public function getRedWinnerOf()
    {
        return $this->redWinnerOf;
    }

    /**
     * @return BabyfootGame
     */
    public function getBlueWinnerOf()
    {
        return $this->blueWinnerOf;
    }

}