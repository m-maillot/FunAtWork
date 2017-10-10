<?php

namespace App\Action\UseCase;

use App\Action\UseCase\Model\PlayerStats;
use App\Resource\Babyfoot\BabyfootGameResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class ComputePlayerStats implements UseCase
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
     * AddGoal constructor.
     * @param BabyfootGameResource $gameResource
     */
    public function __construct(BabyfootGameResource $gameResource)
    {
        $this->gameResource = $gameResource;
        $this->computeStats = new ComputeStats();
    }

    public function execute($organizationId)
    {
        $games = $this->gameResource->select($organizationId, null, false);
        if (count($games) > 0) {
            $this->computeStats->compute($games);
            $playerStats = $this->computeStats->getPlayerStats();
            usort($playerStats, function ($a, $b) {

                /**
                 * @var $a PlayerStats
                 * @var $b PlayerStats
                 */
                return $b->getEloRanking() * 100 - $a->getEloRanking() * 100;
            });
        } else {
            $playerStats = array();
        }
        return new Response(200, "", $playerStats);
    }

}