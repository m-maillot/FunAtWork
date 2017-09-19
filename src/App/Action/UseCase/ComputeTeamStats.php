<?php

namespace App\Action\UseCase;

use App\Resource\Babyfoot\BabyfootGameResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class ComputeTeamStats implements UseCase
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
        $games = $this->gameResource->selectWithoutTournament(null, false);
        $this->computeStats->compute($games);
        return new Response(200, "", $this->computeStats->getTeamStats());
    }

}