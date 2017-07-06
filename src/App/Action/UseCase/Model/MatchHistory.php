<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 7/3/17
 * Time: 10:10 PM
 */

namespace App\Action\UseCase\Model;


use App\Entity\Player;

class MatchHistory
{

    /**
     * @var Player
     */
    private $redPlayer1;

    /**
     * @var int
     */
    private $redPlayer1EloRanking;

    /**
     * @var Player
     */
    private $redPlayer2;

    /**
     * @var int
     */
    private $redPlayer2EloRanking;

    /**
     * @var int
     */
    private $redTeamEloRanking;


    /**
     * @var int
     */
    private $redTeamEloRankingGain;

    /**
     * @var Player
     */
    private $bluePlayer1;

    /**
     * @var int
     */
    private $bluePlayer1EloRanking;

    /**
     * @var Player
     */
    private $bluePlayer2;

    /**
     * @var int
     */
    private $bluePlayer2EloRanking;

    /**
     * @var int
     */
    private $blueTeamEloRanking;

    /**
     * @var int
     */
    private $blueTeamEloRankingGain;

    /**
     * @var int
     */
    private $blueGoals;

    /**
     * @var int
     */
    private $redGoals;

    /**
     * MatchHistory constructor.
     * @param Player $redPlayer1
     * @param int $redPlayer1EloRanking
     * @param Player $redPlayer2
     * @param int $redPlayer2EloRanking
     * @param int $redTeamEloRanking
     * @param int $redTeamEloRankingGain
     * @param Player $bluePlayer1
     * @param int $bluePlayer1EloRanking
     * @param Player $bluePlayer2
     * @param int $bluePlayer2EloRanking
     * @param int $blueTeamEloRanking
     * @param int $blueTeamEloRankingGain
     * @param int $blueGoals
     * @param int $redGoals
     */
    public function __construct(Player $redPlayer1, $redPlayer1EloRanking, Player $redPlayer2, $redPlayer2EloRanking, $redTeamEloRanking, $redTeamEloRankingGain, Player $bluePlayer1, $bluePlayer1EloRanking, Player $bluePlayer2, $bluePlayer2EloRanking, $blueTeamEloRanking, $blueTeamEloRankingGain, $blueGoals, $redGoals)
    {
        $this->redPlayer1 = $redPlayer1;
        $this->redPlayer1EloRanking = $redPlayer1EloRanking;
        $this->redPlayer2 = $redPlayer2;
        $this->redPlayer2EloRanking = $redPlayer2EloRanking;
        $this->redTeamEloRanking = $redTeamEloRanking;
        $this->redTeamEloRankingGain = $redTeamEloRankingGain;
        $this->bluePlayer1 = $bluePlayer1;
        $this->bluePlayer1EloRanking = $bluePlayer1EloRanking;
        $this->bluePlayer2 = $bluePlayer2;
        $this->bluePlayer2EloRanking = $bluePlayer2EloRanking;
        $this->blueTeamEloRanking = $blueTeamEloRanking;
        $this->blueTeamEloRankingGain = $blueTeamEloRankingGain;
        $this->blueGoals = $blueGoals;
        $this->redGoals = $redGoals;
    }

    /**
     * @return int
     */
    public function getRedPlayer1EloRanking()
    {
        return $this->redPlayer1EloRanking;
    }

    /**
     * @return int
     */
    public function getRedPlayer2EloRanking()
    {
        return $this->redPlayer2EloRanking;
    }

    /**
     * @return int
     */
    public function getRedTeamEloRankingGain()
    {
        return $this->redTeamEloRankingGain;
    }

    /**
     * @return int
     */
    public function getBluePlayer1EloRanking()
    {
        return $this->bluePlayer1EloRanking;
    }

    /**
     * @return int
     */
    public function getBluePlayer2EloRanking()
    {
        return $this->bluePlayer2EloRanking;
    }

    /**
     * @return int
     */
    public function getBlueTeamEloRankingGain()
    {
        return $this->blueTeamEloRankingGain;
    }


    /**
     * @return Player
     */
    public function getRedPlayer1()
    {
        return $this->redPlayer1;
    }

    /**
     * @return Player
     */
    public function getRedPlayer2()
    {
        return $this->redPlayer2;
    }

    /**
     * @return int
     */
    public function getRedTeamEloRanking()
    {
        return $this->redTeamEloRanking;
    }

    /**
     * @return Player
     */
    public function getBluePlayer1()
    {
        return $this->bluePlayer1;
    }

    /**
     * @return Player
     */
    public function getBluePlayer2()
    {
        return $this->bluePlayer2;
    }

    /**
     * @return int
     */
    public function getBlueTeamEloRanking()
    {
        return $this->blueTeamEloRanking;
    }

    /**
     * @return int
     */
    public function getBlueGoals()
    {
        return $this->blueGoals;
    }

    /**
     * @return int
     */
    public function getRedGoals()
    {
        return $this->redGoals;
    }
}