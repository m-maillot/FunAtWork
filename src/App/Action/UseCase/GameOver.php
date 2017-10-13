<?php

namespace App\Action\UseCase;

use App\Action\Babyfoot\BabyfootGameOverWSParams;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Resource\Babyfoot\BabyfootGameKnockoutResource;
use App\Resource\Babyfoot\BabyfootGameResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 10:56 AM
 */
class GameOver
{

    /**
     * @var BabyfootGameResource
     */
    private $gameResource;
    /**
     * @var BabyfootGameKnockoutResource
     */
    private $knockoutResource;

    /**
     * GameOver constructor.
     * @param BabyfootGameResource $gameResource
     * @param BabyfootGameKnockoutResource $knockoutResource
     */
    public function __construct(BabyfootGameResource $gameResource, BabyfootGameKnockoutResource $knockoutResource)
    {
        $this->gameResource = $gameResource;
        $this->knockoutResource = $knockoutResource;
    }

    /**
     * @param BabyfootGameOverWSParams $params
     * @return Response
     */
    public function execute(BabyfootGameOverWSParams $params)
    {
        $game = $this->gameResource->selectOne($params->getConnectedUser()->getOrganization()->getId(), $params->getGameId());
        if (!$game) {
            return new Response(404, 'Game not found.');
        }
        if ($game->getStatus() !== BabyfootGame::GAME_STARTED) {
            return new Response(400, 'Game is already over.');
        }
        if ($game->getCreator()->getId() !== $params->getConnectedUser()->getId()) {
            return new Response(400, 'Only the creator can close the game.');
        }
        $game->setStatus($params->getGameId() ? BabyfootGame::GAME_CANCELED : BabyfootGame::GAME_OVER);
        $game->setEndedDate(new \DateTime());
        $game = $this->gameResource->createOrUpdate($game);
        if ($game->getTournament() != null) {
            $blueScore = BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());
            $redScore = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam());

            $this->updateNextGame($game, $redScore > $blueScore);
        }
        return new Response(200, "", $game);

    }

    /**
     * Update the next round
     * @param BabyfootGame $game
     * @param $redWinner bool
     */
    private function updateNextGame(BabyfootGame $game, $redWinner)
    {
        $knockoutNextGame = $this->knockoutResource->selectNextGame($game->getTournament()->getId(), $game->getId(), true);
        if ($knockoutNextGame) {
            $gameToUpdate = $knockoutNextGame->getGame();
            if ($redWinner) {
                $gameToUpdate->setRedTeam($game->getRedTeam());
            } else {
                $gameToUpdate->setRedTeam($game->getBlueTeam());
            }
            $this->gameResource->createOrUpdate($gameToUpdate);
        } else {
            $knockoutNextGame = $this->knockoutResource->selectNextGame($game->getTournament()->getId(), $game->getId(), false);
            if ($knockoutNextGame) {
                $gameToUpdate = $knockoutNextGame->getGame();
                if ($redWinner) {
                    $gameToUpdate->setBlueTeam($game->getRedTeam());
                } else {
                    $gameToUpdate->setBlueTeam($game->getBlueTeam());
                }
                $this->gameResource->createOrUpdate($gameToUpdate);
            }
        }

    }
}