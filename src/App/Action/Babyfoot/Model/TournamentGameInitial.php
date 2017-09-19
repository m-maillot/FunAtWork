<?php

namespace App\Action\Babyfoot\Model;


/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 10:27 PM
 */
class TournamentGameInitial implements TournamentGame
{

    /**
     * @var int
     */
    private $tempGameId;
    /**
     * @var int
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
     * TournamentGameInitial constructor.
     * @param int $tempGameId
     * @param int $plannedDate
     * @param int $redAttackId
     * @param int $redDefenseId
     * @param int $blueAttackId
     * @param int $blueDefenseId
     */
    public function __construct($tempGameId, $plannedDate, $redAttackId, $redDefenseId, $blueAttackId, $blueDefenseId)
    {
        $this->tempGameId = $tempGameId;
        $this->plannedDate = $plannedDate;
        $this->redAttackId = $redAttackId;
        $this->redDefenseId = $redDefenseId;
        $this->blueAttackId = $blueAttackId;
        $this->blueDefenseId = $blueDefenseId;
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

}