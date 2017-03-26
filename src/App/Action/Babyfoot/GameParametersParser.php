<?php

namespace App\Action\Babyfoot;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/25/17
 * Time: 7:19 PM
 */
class GameParametersParser
{

    /**
     * @param ServerRequestInterface $request
     * @return int|null
     */
    public function parseFetchOneGame(ServerRequestInterface $request)
    {
        $gameId = $request->getParsedBody()['game'];
        return $gameId;
    }

    public function parseCreateGame(ServerRequestInterface $request)
    {
        $bluePlayerAttackId = $request->getParsedBody()['redPlayerAttackId'];
        $bluePlayerDefenseId = $request->getParsedBody()['redPlayerDefenseId'];
        $redPlayerAttackId = $request->getParsedBody()['bluePlayerAttackId'];
        $redPlayerDefenseId = $request->getParsedBody()['bluePlayerDefenseId'];
        return new BabyfootCreateGameWSParams($redPlayerAttackId, $redPlayerDefenseId, $bluePlayerAttackId, $bluePlayerDefenseId);
    }

    public function parseAddGoal(ServerRequestInterface $request)
    {
        $strikerId = $request->getParsedBody()['striker'];
        $position = $request->getParsedBody()['position'];
        $gamelle = $request->getParsedBody()['gamelle'] === '1';
        $gameId = $request->getParsedBody()['game'];
        return new BabyfootAddGoalWSParams($strikerId, $position, $gamelle, $gameId);
    }

    public function parseGameOver(ServerRequestInterface $request)
    {
        $gameId = $request->getParsedBody()['game'];
        $canceled = $request->getParsedBody()['cancelled'] === '1';
        return new BabyfootGameOverWSParams($gameId, $canceled);
    }
}