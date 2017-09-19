<?php

namespace App\Action\Babyfoot\Model;


/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 10:27 PM
 */
class TournamentGame
{

    /**
     * @var int
     */
    private $tempGameId;
    /**
     * @var int
     */
    private $round;
    /**
     * @var \DateTime
     */
    private $plannedDate;
    /**
     * @var int
     */
    private $redAttackId;
    /**
     * @var int
     */
    private $redDefenseId;
    /**
     * @var int
     */
    private $blueAttackId;
    /**
     * @var int
     */
    private $blueDefenseId;
    /**
     * @var int
     */
    private $redWinnerOfGameId;
    /**
     * @var int
     */
    private $blueWinnerOfGameId;

    /**
     * TournamentGame constructor.
     * @param int $tempGameId
     * @param int $round
     * @param \DateTime $plannedDate
     * @param int $redAttackId
     * @param int $redDefenseId
     * @param int $blueAttackId
     * @param int $blueDefenseId
     * @param int $redWinnerOfGameId
     * @param int $blueWinnerOfGameId
     */
    public function __construct($tempGameId, $round, \DateTime $plannedDate,
                                $redAttackId, $redDefenseId, $blueAttackId, $blueDefenseId,
                                $redWinnerOfGameId, $blueWinnerOfGameId)
    {
        $this->tempGameId = $tempGameId;
        $this->round = $round;
        $this->plannedDate = $plannedDate;
        $this->redAttackId = $redAttackId;
        $this->redDefenseId = $redDefenseId;
        $this->blueAttackId = $blueAttackId;
        $this->blueDefenseId = $blueDefenseId;
        $this->redWinnerOfGameId = $redWinnerOfGameId;
        $this->blueWinnerOfGameId = $blueWinnerOfGameId;
    }

    /**
     * @return int
     */
    public function getTempGameId()
    {
        return $this->tempGameId;
    }

    /**
     * @return int
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return \DateTime
     */
    public function getPlannedDate()
    {
        return $this->plannedDate;
    }

    /**
     * @return int
     */
    public function getRedAttackId()
    {
        return $this->redAttackId;
    }

    /**
     * @return int
     */
    public function getRedDefenseId()
    {
        return $this->redDefenseId;
    }

    /**
     * @return int
     */
    public function getBlueAttackId()
    {
        return $this->blueAttackId;
    }

    /**
     * @return int
     */
    public function getBlueDefenseId()
    {
        return $this->blueDefenseId;
    }

    /**
     * @return int
     */
    public function getRedWinnerOfGameId()
    {
        return $this->redWinnerOfGameId;
    }

    /**
     * @return int
     */
    public function getBlueWinnerOfGameId()
    {
        return $this->blueWinnerOfGameId;
    }

    /**
     * @return bool
     */
    public function isInFirstPool()
    {
        return $this->blueDefenseId > 0 && $this->redDefenseId > 0 &&
            $this->blueAttackId > 0 && $this->blueDefenseId > 0;
    }
}