<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:41 PM
 */

namespace App\Action\UseCase;


use App\Action\Babyfoot\Model\TournamentGameInitial;
use App\Action\Babyfoot\Model\TournamentGameKnockout;
use App\Action\Player\CreateTournamentWSParams;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGameKnockout;
use App\Entity\Babyfoot\BabyfootTournament;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootTeamResource;
use App\Resource\Babyfoot\BabyfootTournamentResource;
use App\Resource\PlayerResource;

class CreateTournament implements UseCase
{

    /**
     * @var BabyfootTournamentResource
     */
    private $tournamentResource;
    /**
     * @var TeamManagement
     */
    private $teamManagement;
    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * CreateTournament constructor.
     * @param BabyfootTournamentResource $tournamentResource
     * @param BabyfootGameResource $gameResource
     * @param BabyfootTeamResource $teamResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootTournamentResource $tournamentResource, BabyfootGameResource $gameResource,
                                BabyfootTeamResource $teamResource, PlayerResource $playerResource)
    {
        $this->tournamentResource = $tournamentResource;
        $this->gameResource = $gameResource;
        $this->teamManagement = new TeamManagement($teamResource, $playerResource);
    }


    /**
     * @param Player $registerUser
     * @param CreateTournamentWSParams $params
     * @return Response
     */
    public function execute(Player $registerUser, CreateTournamentWSParams $params)
    {
        $tournament = new BabyfootTournament(0, 0, $params->getName(), $params->getStartedDate(), null, $params->getOrganisation());
        $tournament = $this->tournamentResource->createOrUpdate($tournament);
        // Create each game for the tournament
        $tempIdMatchInit = $this->createInitialsGames($tournament, $registerUser, $params->getInitalGames());
        $this->createKnockoutGames($tournament, $registerUser, $params->getKnockoutGames(), $tempIdMatchInit);

        return new Response(201, "Tournament created");
    }

    /**
     * @param BabyfootTournament $tournament
     * @param Player $creator
     * @param TournamentGameInitial[] $games
     * @return array
     */
    private function createInitialsGames(BabyfootTournament $tournament, Player $creator, array $games)
    {
        $tempIdMatch = array();
        foreach ($games as $game) {
            // TODO  Check if other game is running for this organization, should deny request
            $redTeam = $this->teamManagement->createTeam($creator->getOrganization()->getId(), $game->getRedAttackId(), $game->getRedDefenseId());
            $blueTeam = $this->teamManagement->createTeam($creator->getOrganization()->getId(), $game->getBlueAttackId(), $game->getRedDefenseId());

            if ($blueTeam && $redTeam) {
                // Create the game
                $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, $blueTeam, $redTeam,
                    null, $game->getPlannedDate(), null, $creator, $creator->getOrganization(), $tournament);
                $game = $this->gameResource->createOrUpdate($game);
                $tempIdMatch[$game->getId()] = $game->getId();
            }
        }
        return $tempIdMatch;
    }

    /**
     * @param BabyfootTournament $tournament
     * @param Player $creator
     * @param TournamentGameKnockout[] $games
     * @param array $tempIdMatchInit
     * @return array
     */
    private function createKnockoutGames(BabyfootTournament $tournament, Player $creator, array $games, array $tempIdMatchInit)
    {
        foreach ($games as $game) {
            $redGameId = $tempIdMatchInit[$game->getRedWinnerOfGameId()];
            $blueGameId = $tempIdMatchInit[$game->getBlueWinnerOfGameId()];
            // Create the game
            $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, null, null,
                null, $game->getPlannedDate(), null, $creator, $creator->getOrganization(), $tournament);
            $game = $this->gameResource->createOrUpdate($game);
            $redGame = $this->gameResource->selectOne($tournament->getOrganization()->getId(), $redGameId);
            $blueGame = $this->gameResource->selectOne($tournament->getOrganization()->getId(), $blueGameId);
            new BabyfootGameKnockout($tournament, $game, $redGame, $blueGame);
        }
    }
}