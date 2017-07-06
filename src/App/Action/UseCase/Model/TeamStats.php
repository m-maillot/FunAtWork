<?php

namespace App\Action\UseCase\Model;

use App\Entity\Player;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/22/17
 * Time: 9:27 PM
 */
class TeamStats
{
    public $id = 1;

    /**
     * @var Player
     */
    public $player1;

    /**
     * @var Player
     */
    public $player2;
    /**
     * @var int
     */
    public $victory = 0;
    /**
     * @var int
     */
    public $loose = 0;
    /**
     * @var int
     */
    public $goalAverage = 0;
    /**
     * @var int
     */
    public $ballsPlayed = 0;
    /**
     * @var int
     */
    public $goal = 0;
    /**
     * @var int
     */
    public $gamePlayed = 0;
    /**
     * @var int
     */
    public $goalAttack = 0;
    /**
     * @var int
     */
    public $goalDefense = 0;
    /**
     * @var int
     */
    public $ballsPlayedAttack = 0;
    /**
     * @var int
     */
    public $ballsPlayedDefense = 0;
    /**
     * @var int
     */
    public $eloRanking = 1500;

}