<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/22/17
 * Time: 10:13 PM
 */

namespace App\Action\UseCase\Model;


use App\Entity\Player;

class PlayerStats
{

    /**
     * @var Player
     */
    public $player;
    /**
     * @var int count of goals
     */
    public $goal = 0;
    /**
     * @var int count of goal when player is attack
     */
    public $goalAttack = 0;
    /**
     * @var int count of goal when player is defense
     */
    public $goalDefense = 0;
    /**
     * @var int count of victories
     */
    public $victory = 0;
    /**
     * @var int count of loose
     */
    public $loose = 0;
    /**
     * @var int total of game played
     */
    public $gamePlayed = 0;
    /**
     * @var int goal average by match (for each game, with substitute conceded goals
     */
    public $goalAverage = 0;
    /**
     * @var int goal average by match (for each game, with substitute conceded goals
     */
    public $goals = 0;
    /**
     * @var int count of balls played
     */
    public $ballsPlayed = 0;
    /**
     * @var int count of balls played when player is attack
     */
    public $ballsPlayedAttack = 0;
    /**
     * @var int count of balls played when player is defense
     */
    public $ballsPlayedDefense = 0;

    public $goalConcede = 0;
    /**
     * @var int
     */
    private $eloRanking = 1500;

    /**
     * @var MatchHistory[]
     */
    public $matchsHistory = array();

    /**
     * @param $incValue int
     */
    public function incEloRanking($incValue)
    {
        $this->eloRanking += $incValue;
    }

    /**
     * @return int
     */
    public function getEloRanking()
    {
        if ($this->player->getId() === 17) {
            // Guest, so set elo to 1000
            return 1500;
        }
        return $this->eloRanking;
    }

    /**
     * @var array with W when player wins game and L when player loose game
     */
    public $gameSeries = array();
    /**
     * @var int percent of goal per ball
     */
    public $percentGoalPerBall = 0;
    /**
     * @var int average of goal per game
     */
    public $avgGoalPerGame = 0;
    /**
     * @var int pervent of victory
     */
    public $percentVictory = 0;
    /**
     * @var int percent of loose
     */
    public $percentLoose = 0;

}