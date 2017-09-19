<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:37 PM
 */

namespace App\Action\Babyfoot;


use App\Action\Babyfoot\Model\TournamentGameInitial;
use App\Action\Babyfoot\Model\TournamentGameKnockout;
use App\Action\Player\CreateTournamentWSParams;
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
        $json = $request->getParsedBody();
        $data = json_decode($json, true);
        $name = $data['name'];
        $plannedDate = $data['plannedDate'];
        $initialMatches = $data['initial_matches'];

        $initGames = array();
        foreach ($initialMatches as $initialMatch) {
            $tempId = $initialMatch['id'];
            $plannedDate = $initialMatch['plannedDate'];
            array_push($initGames, new TournamentGameInitial($tempId,
                $plannedDate,
                $initialMatch['redAttackPlayerId'],
                $initialMatch['redDefensePlayerId'],
                $initialMatch['blueAttackPlayerId'],
                $initialMatch['blueDefensePlayerId']));
        }

        $knockoutGamesJson = $data['knockout_games'];
        $knockoutGames = array();
        foreach ($knockoutGamesJson as $knockoutGame) {
            $tempId = $knockoutGame['id'];
            $plannedDate = $knockoutGame['plannedDate'];
            array_push($knockoutGames, new TournamentGameKnockout($tempId,
                $plannedDate, /*TODO Get red game id*/
                $knockoutGame['redWinnerOfGameId'], /*TODO Get blue game id*/
                $knockoutGame['blueWinnerOfGameId']));
        }

        return new CreateTournamentWSParams($plannedDate, $name, $connectedUser->getOrganization(), $initGames, $knockoutGames);
    }
}