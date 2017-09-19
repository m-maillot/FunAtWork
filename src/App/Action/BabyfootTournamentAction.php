<?php

namespace App\Action;

use App\Action\Babyfoot\GameParametersParser;
use App\Action\Babyfoot\TournamentParametersParser;
use App\Action\UseCase\AddGoal;
use App\Action\UseCase\ComputePlayerStats;
use App\Action\UseCase\ComputeTeamStats;
use App\Action\UseCase\ComputeUniquePlayerStats;
use App\Action\UseCase\GameOver;
use App\Action\UseCase\Model\PlayerStatsMapper;
use App\Action\UseCase\Model\TeamStatsMapper;
use App\Action\UseCase\StartNewGame;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\Babyfoot\BabyfootTeamResource;
use App\Resource\Babyfoot\BabyfootTournamentResource;
use App\Resource\PlayerResource;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Perform action on babyfoot tournament: start a tournament
 *
 * @package App\Action
 */
class BabyfootTournamentAction
{

    private $logger;
    private $teamResource;
    private $gameResource;
    private $goalResource;
    private $playerResource;
    private $tournamentResource;
    private $parameterParser;

    public function __construct(Logger $logger, BabyfootTeamResource $teamResource, BabyfootGameResource $gameResource,
                                BabyfootGoalResource $goalResource, PlayerResource $playerResource,
                                BabyfootTournamentResource $tournamentResource)
    {
        $this->logger = $logger;
        $this->teamResource = $teamResource;
        $this->gameResource = $gameResource;
        $this->goalResource = $goalResource;
        $this->playerResource = $playerResource;
        $this->tournamentResource = $tournamentResource;
        $this->parameterParser = new TournamentParametersParser();
    }

    public function createTournament(ServerRequestInterface $request, Response $response, $args)
    {
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);

        $params = $this->parameterParser->parseCreateTournament($request);
        // TODO Create all teams
        // TODO Create tournament
        // TODO Create all matchs for this tournament
        // TODO For each match, create a GameKnockout

        return $response->withJSON(BabyfootGameArrayMapper::transforms($games));
    }
}