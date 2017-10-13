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
     * GameOver constructor.
     * @param BabyfootGameResource $gameResource
     */
    public function __construct(BabyfootGameResource $gameResource)
    {
        $this->gameResource = $gameResource;
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
        $game->setStatus(BabyfootGame::GAME_CANCELED);
        $game->setEndedDate(new \DateTime());
        $game = $this->gameResource->createOrUpdate($game);
        return new Response(200, "", $game);

    }
}