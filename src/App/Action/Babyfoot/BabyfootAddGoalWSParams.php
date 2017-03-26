<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class BabyfootAddGoalWSParams
{

    /**
     * @var int
     */
    private $strikerId;

    /**
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $gamelle;

    /**
     * @var int
     */
    private $gameId;

    /**
     * BabyfootAddGoalWSParams constructor.
     * @param int $strikerId
     * @param int $position
     * @param bool $gamelle
     * @param int $gameId
     */
    public function __construct($strikerId, $position, $gamelle, $gameId)
    {
        $this->strikerId = $strikerId;
        $this->position = $position;
        $this->gamelle = $gamelle;
        $this->gameId = $gameId;
    }

    /**
     * @return int
     */
    public function getStrikerId()
    {
        return $this->strikerId;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isGamelle()
    {
        return $this->gamelle;
    }

    /**
     * @return int
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->gameId && $this->gameId > 0
            && $this->strikerId && $this->strikerId > 0
            && $this->position;
    }
}