<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class StartGameTournamentWSParams
{

    /**
     * @var int
     */
    private $gameId;

    /**
     * BabyfootCreateGameWSParams constructor.
     * @param int $gameId
     */
    public function __construct($gameId)
    {
        $this->gameId = $gameId;
    }

    /**
     * @return int
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    public function isValid()
    {
        return $this->gameId;
    }
}