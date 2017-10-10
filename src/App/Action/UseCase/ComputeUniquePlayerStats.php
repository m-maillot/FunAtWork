<?php

namespace App\Action\UseCase;

use App\Action\UseCase\Model\PlayerStats;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\PlayerResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class ComputeUniquePlayerStats implements UseCase
{
    /**
     * @var PlayerResource
     */
    private $playerResource;

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
     * @param PlayerResource $playerResource
     * @param $playerId int
     */
    public function __construct(BabyfootGameResource $gameResource,
                                PlayerResource $playerResource,
                                $playerId)
    {
        $this->gameResource = $gameResource;
        $this->playerResource = $playerResource;
        $this->playerId = $playerId;
        $this->computeStats = new ComputeStats();
    }

    public function execute($organizationId)
    {
        $games = $this->gameResource->select($organizationId, null, false);
        if ($games > 0) {
            $this->computeStats->compute($games);
            $stat = $this->computeStats->getPlayerStatsById($this->playerId);
        } else {
            $stat = new PlayerStats();
            $stat->rank = -1;
            $stat->goals = 0;
            $stat->gamePlayed = 0;
            $stat->player = $this->playerResource->selectOne($this->playerId, $organizationId);
        }
        return new Response(200, "", $stat);
    }

}