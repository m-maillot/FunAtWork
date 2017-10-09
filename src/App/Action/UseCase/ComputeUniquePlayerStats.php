<?php

namespace App\Action\UseCase;

use App\Resource\Babyfoot\BabyfootGameResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class ComputeUniquePlayerStats implements UseCase
{

    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * @var ComputeStats
     */
    private $computeStats;

    /**
     * @var int
     */
    private $playerId;

    /**
     * AddGoal constructor.
     * @param BabyfootGameResource $gameResource
     * @param $playerId int
     */
    public function __construct(BabyfootGameResource $gameResource, $playerId)
    {
        $this->gameResource = $gameResource;
        $this->playerId = $playerId;
        $this->computeStats = new ComputeStats();
    }

    public function execute()
    {
        $games = $this->gameResource->select(null, null, false);
        $this->computeStats->compute($games);
        return new Response(200, "", $this->computeStats->getPlayerStatsById($this->playerId));
    }

}