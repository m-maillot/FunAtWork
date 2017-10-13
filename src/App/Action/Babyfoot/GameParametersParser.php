<?php

namespace App\Action\Babyfoot;

use App\Action\Babyfoot\Model\GoalGame;
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
        $gameId = (int)$request->getParsedBody()['game'];
        return $gameId;
    }

    public function parseCreateGame(ServerRequestInterface $request)
    {
        $bluePlayerAttackId = (int)$request->getParsedBody()['redPlayerAttackId'];
        $bluePlayerDefenseId = (int)$request->getParsedBody()['redPlayerDefenseId'];
        $redPlayerAttackId = (int)$request->getParsedBody()['bluePlayerAttackId'];
        $redPlayerDefenseId = (int)$request->getParsedBody()['bluePlayerDefenseId'];
        return new BabyfootCreateGameWSParams($redPlayerAttackId, $redPlayerDefenseId, $bluePlayerAttackId, $bluePlayerDefenseId);
    }

    public function parseAddGoal(ServerRequestInterface $request)
    {
        $strikerId = (int)$request->getParsedBody()['striker'];
        $position = (int)$request->getParsedBody()['position'];
        $gamelle = $request->getParsedBody()['gamelle'] === '1';
        $gameId = (int)$request->getParsedBody()['game'];
        return new BabyfootAddGoalWSParams($strikerId, $position, $gamelle, $gameId);
    }

    public function parseGameOver(ServerRequestInterface $request)
    {
        $connectedUser = $request->getAttribute("auth_user", null);
        $data = $request->getParsedBody();
        $gameId = $data['gameId'];
        $isCanceled = $data['canceled'] == '1';
        $goalsJson = $data['goals'];
        $goals = array();
        foreach ($goalsJson as $game) {
            array_push($games, new GoalGame($goalsJson['striker'],
                $goalsJson['position'],
                $game['gamelle']));
        }
        return new BabyfootGameOverWSParams($connectedUser, $gameId, $isCanceled, $goals);
    }
}