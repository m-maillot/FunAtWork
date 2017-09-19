<?php

namespace App\Action\Babyfoot\Model;


/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 10:27 PM
 */
class TournamentGameKnockout implements TournamentGame
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
    private $redWinnerOfGameId;
    /**
     * @var int
     */
    private $blueWinnerOfGameId;

    /**
     * TournamentGameKnockout constructor.
     * @param int $tempGameId
     * @param int $plannedDate
     * @param int $redWinnerOfGameId
     * @param int $blueWinnerOfGameId
     */
    public function __construct($tempGameId, $plannedDate, $redWinnerOfGameId, $blueWinnerOfGameId)
    {
        $this->tempGameId = $tempGameId;
        $this->plannedDate = $plannedDate;
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
    public function getPlannedDate()
    {
        return $this->plannedDate;
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
}