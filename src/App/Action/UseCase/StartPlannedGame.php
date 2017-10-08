<?php

namespace App\Action\UseCase;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;

class StartPlannedGame implements UseCase
{

    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * StartNewGame constructor.
     * @param BabyfootGameResource $gameResource
     */
    public function __construct(BabyfootGameResource $gameResource)
    {
        $this->gameResource = $gameResource;
    }

    /**
     * @param Player $creator
     * @param int $gameId
     * @return Response
     */
    public function execute(Player $creator, $gameId)
    {
        $organizationId = $creator->getOrganization()->getId();
        $game = $this->gameResource->selectOne($organizationId, $gameId);

        if (!$game) {
            return new Response(404, "Game not found");
        }

        if ($game->getCreator()->getId() != $creator->getId()) {
            return new Response(401, "Only the creator can start the game");
        }

        if ($game->getStatus() != BabyfootGame::GAME_PLANNED) {
            return new Response(400, "Can't start game (already started or game over)");
        }
        $game->setStartedDate(new \DateTime());
        $game->setStatus(BabyfootGame::GAME_STARTED);
        $game = $this->gameResource->createOrUpdate($game);
        return new Response(200, "Game started", $game);
    }
}