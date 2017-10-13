<?php

namespace App\Action\Babyfoot;

use App\Action\Babyfoot\Model\GoalGame;
use App\Entity\Player;

/**
 *
 */
class BabyfootGameOverWSParams
{

    /**
     * @var Player
     */
    private $connectedUser;
    /**
     * @var int
     */
    private $gameId;

    /**
     * @var bool
     */
    private $canceled;

    /**
     * @var GoalGame[]
     */
    private $goals;

    /**
     * BabyfootGameOverWSParams constructor.
     * @param $connectedUser
     * @param int $gameId
     * @param bool $canceled
     * @param $goals
     */
    public function __construct($connectedUser, $gameId, $canceled, $goals)
    {
        $this->connectedUser = $connectedUser;
        $this->gameId = $gameId;
        $this->canceled = $canceled;
        $this->goals = $goals;
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
    public function isCanceled()
    {
        return $this->canceled;
    }

    /**
     * @return GoalGame[]
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @return Player
     */
    public function getConnectedUser()
    {
        return $this->connectedUser;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->gameId && $this->gameId > 0;
    }
}