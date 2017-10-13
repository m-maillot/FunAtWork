<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:41 PM
 */

namespace App\Action\UseCase;


use App\Action\Babyfoot\CreateTournamentWSParams;
use App\Action\Babyfoot\Model\TournamentGame;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGameKnockout;
use App\Entity\Babyfoot\BabyfootTournament;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameKnockoutResource;
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
     * @var BabyfootGameKnockoutResource
     */
    private $gameKnockoutResource;

    /**
     * CreateTournament constructor.
     * @param BabyfootTournamentResource $tournamentResource
     * @param BabyfootGameResource $gameResource
     * @param BabyfootTeamResource $teamResource
     * @param PlayerResource $playerResource
     * @param BabyfootGameKnockoutResource $babyfootGameKnockoutResource
     */
    public function __construct(BabyfootTournamentResource $tournamentResource, BabyfootGameResource $gameResource,
                                BabyfootTeamResource $teamResource, PlayerResource $playerResource,
                                BabyfootGameKnockoutResource $babyfootGameKnockoutResource)
    {
        $this->tournamentResource = $tournamentResource;
        $this->gameResource = $gameResource;
        $this->teamManagement = new TeamManagement($teamResource, $playerResource);
        $this->gameKnockoutResource = $babyfootGameKnockoutResource;
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
        $this->createGames($tournament, $registerUser, $params->getGames());

        return new Response(201, "Tournament created");
    }

    /**
     * @param BabyfootTournament $tournament
     * @param Player $creator
     * @param TournamentGame[] $games
     */
    private function createGames(BabyfootTournament $tournament, Player $creator, array $games)
    {
        $tempIdMatch = array();
        foreach ($games as $game) {
            if ($game->isInFirstPool()) {
                $redTeam = $this->teamManagement->createTeam($creator->getOrganization()->getId(), $game->getRedAttackId(), $game->getRedDefenseId());
                $blueTeam = $this->teamManagement->createTeam($creator->getOrganization()->getId(), $game->getBlueAttackId(), $game->getBlueDefenseId());
            } else {
                $redTeam = null;
                $blueTeam = null;
            }

            // Create the game
            $createdGame = new BabyfootGame(0, BabyfootGame::GAME_PLANNED, $blueTeam, $redTeam,
                BabyfootGame::GAME_MODE_TIME, 7,
                null, $game->getPlannedDate(), null, $creator, $creator->getOrganization(), $tournament);
            $createdGame = $this->gameResource->createOrUpdate($createdGame);

            $knockout = new BabyfootGameKnockout(0, $game->getRound(), $tournament, $createdGame,
                $this->loadKnockoutGame($tempIdMatch[$game->getRedWinnerOfGameId()]),
                $this->loadKnockoutGame($tempIdMatch[$game->getBlueWinnerOfGameId()]));
            $this->gameKnockoutResource->createOrUpdate($knockout);
            $tempIdMatch[$game->getTempGameId()] = $knockout->getId();
        }
    }

    /**
     * @param $knockoutId
     * @return BabyfootGameKnockout|null
     */
    private function loadKnockoutGame($knockoutId)
    {
        if ($knockoutId) {
            return $this->gameKnockoutResource->selectOne($knockoutId);
        }
        return null;
    }
}