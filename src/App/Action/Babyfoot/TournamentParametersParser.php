<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:37 PM
 */

namespace App\Action\Babyfoot;


use App\Action\Babyfoot\Model\TournamentGame;
use App\Entity\Player;
use Psr\Http\Message\ServerRequestInterface;

class TournamentParametersParser
{

    /**
     * @param ServerRequestInterface $request
     * @return CreateTournamentWSParams
     */
    public function parseCreateTournament(ServerRequestInterface $request)
    {
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);
        $data = $request->getParsedBody();
        $name = $data['name'];
        $startDateTimestamp = $data['plannedDate'];
        $gamesJson = $data['games'];

        /**
         * @var TournamentGame[] $games
         */
        $games = array();
        foreach ($gamesJson as $game) {
            $tempId = $game['id'];
            $plannedDateTimestamp = $game['plannedDate'];
            $plannedDate = new \DateTime();
            $plannedDate->setTimestamp($plannedDateTimestamp);
            array_push($games, new TournamentGame($tempId,
                $game['round'],
                $plannedDate,
                $game['redAttackPlayerId'],
                $game['redDefensePlayerId'],
                $game['blueAttackPlayerId'],
                $game['blueDefensePlayerId'],
                $game['redWinnerOfGameId'],
                $game['blueWinnerOfGameId']));
        }

        $startDate = new \DateTime();
        $startDate->setTimestamp($startDateTimestamp);
        return new CreateTournamentWSParams($startDate, $name, $connectedUser->getOrganization(), $games);
    }
}