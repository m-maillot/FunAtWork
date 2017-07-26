<?php

namespace App\Action\UseCase;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGoal;
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
class AddGoal implements UseCase
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
     * @param Player $creator
     * @param int $gameId
     * @param int $strikerId
     * @param $position int
     * @param $gamelle bool
     * @return Response
     */
    public function execute(Player $creator, $gameId, $strikerId, $position, $gamelle)
    {
        $game = $this->gameResource->selectOne($gameId);
        if (!$game) {
            return new Response(400, 'Game not found.');
        }
        if ($game->getStatus() !== BabyfootGame::GAME_STARTED) {
            return new Response(400, 'Game is over.');
        }
        if ($game->getCreator()->getId() !== $creator->getId()) {
            return new Response(400, 'Only the creator can add a goal.');
        }
        if (!$this->checkPlayerId($game, $strikerId)) {
            return new Response(400, 'Player not found in this game');
        }
        $striker = $this->playerResource->selectOne($strikerId);
        if (!$striker) {
            return new Response(400, 'Player not found');
        }
        $goal = new BabyfootGoal(0, new \DateTime(), $striker, $position, $gamelle, $game);
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


    private function checkPlayerId(BabyfootGame $game, $strikerId)
    {
        return $game->getRedTeam()->getPlayerAttack()->getId() == $strikerId
            || $game->getRedTeam()->getPlayerDefense()->getId() == $strikerId
            || $game->getBlueTeam()->getPlayerAttack()->getId() == $strikerId
            || $game->getBlueTeam()->getPlayerDefense()->getId() == $strikerId;
    }
}