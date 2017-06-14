<?php

namespace App\Action\UseCase;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGoal;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\PlayerResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class AddGoalConnected implements UseCase
{

    /**
     * @var BabyfootGoalResource
     */
    private $goalResource;

    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * @var PlayerResource
     */
    private $playerResource;

    /**
     * AddGoal constructor.
     * @param BabyfootGoalResource $goalResource
     * @param BabyfootGameResource $gameResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootGoalResource $goalResource, BabyfootGameResource $gameResource,
                                PlayerResource $playerResource)
    {
        $this->goalResource = $goalResource;
        $this->gameResource = $gameResource;
        $this->playerResource = $playerResource;
    }


    /**
     * @param boolean $isRedTeam
     * @param boolean $isAttack
     * @param boolean $isGamelle
     * @return Response
     */
    public function execute($isRedTeam, $isAttack, $isGamelle)
    {
        $game = $this->gameResource->selectCurrent();
        if (!$game) {
            return new Response(400, 'Game not found.');
        }
        if ($game->getStatus() !== BabyfootGame::GAME_STARTED) {
            return new Response(400, 'Game is over.');
        }

        $team = $isRedTeam ? $game->getRedTeam() : $game->getBlueTeam();
        $striker = $isAttack ? $team->getPlayerAttack() : $team->getPlayerDefense();
        if (!$striker) {
            return new Response(400, 'Player not found');
        }
        $goal = new BabyfootGoal(0, new \DateTime(), $striker, $isAttack ? 1 : 2, $isGamelle, $game);
        $goal = $this->goalResource->create($goal);
        $game->getGoals()->add($goal);
        $blueScore = BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());
        $redScore = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam());
        if ($blueScore >= 10 || $redScore >= 10) {
            $game->setStatus(BabyfootGame::GAME_OVER);
            $this->gameResource->createOrUpdate($game);
        }
        return new Response(200, "", $game);
    }
}