<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class BabyfootGameSelectWSParams
{

    /**
     * @var int
     */
    private $gameId;

    /**
     * @var int
     */
    private $status;

    /**
     * BabyfootGameOverWSParams constructor.
     * @param int $gameId
     * @param bool $status
     */
    public function __construct($gameId, $status)
    {
        $this->gameId = $gameId;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->gameId && $this->gameId > 0;
    }
}