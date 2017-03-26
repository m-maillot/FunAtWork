<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class BabyfootGameOverWSParams
{

    /**
     * @var int
     */
    private $gameId;

    /**
     * @var bool
     */
    private $canceled;

    /**
     * BabyfootGameOverWSParams constructor.
     * @param int $gameId
     * @param bool $canceled
     */
    public function __construct($gameId, $canceled)
    {
        $this->gameId = $gameId;
        $this->canceled = $canceled;
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
     * @return bool
     */
    public function isValid()
    {
        return $this->gameId && $this->gameId > 0;
    }
}