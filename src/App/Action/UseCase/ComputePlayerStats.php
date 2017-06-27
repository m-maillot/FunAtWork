<?php

namespace App\Action\UseCase;

use App\Action\UseCase\Model\PlayerStats;
use App\Action\UseCase\Model\TeamStats;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\PlayerResource;

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

    public function execute()
    {
        $games = $this->gameResource->select(null, false);
        $this->computeStats->compute($games);
        return new Response(200, "", $this->computeStats->getPlayerStats());
    }

}