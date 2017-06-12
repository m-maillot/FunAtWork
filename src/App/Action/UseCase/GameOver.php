<?php

namespace App\Action\UseCase;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
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
     * @param $gameId int
     * @param $shouldBeCancelled bool
     * @return Response
     */
    public function execute($gameId, $shouldBeCancelled)
    {
        $game = $this->gameResource->selectOne($gameId);
        if (!$game) {
            return new Response(404, 'Game not found.');
        }
        if ($game->getStatus() !== BabyfootGame::GAME_STARTED) {
            return new Response(400, 'Game is already over.');
        }
        $game->setStatus($shouldBeCancelled ? BabyfootGame::GAME_CANCELED : BabyfootGame::GAME_OVER);
        $game = $this->gameResource->createOrUpdate($game);
        return new Response(200, "", BabyfootGameArrayMapper::transform($game));

    }
}