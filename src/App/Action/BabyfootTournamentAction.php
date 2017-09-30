<?php

namespace App\Action;

use App\Action\Babyfoot\TournamentParametersParser;
use App\Action\UseCase\CreateTournament;
use App\Action\UseCase\StartPlannedGame;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Babyfoot\Mapper\BabyfootTournamentArrayMapper;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameKnockoutResource;
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
    private $knockoutResource;
    private $parameterParser;

    public function __construct(Logger $logger, BabyfootTeamResource $teamResource, BabyfootGameResource $gameResource,
                                BabyfootGoalResource $goalResource, PlayerResource $playerResource,
                                BabyfootTournamentResource $tournamentResource,
                                BabyfootGameKnockoutResource $knockoutResource)
    {
        $this->logger = $logger;
        $this->teamResource = $teamResource;
        $this->gameResource = $gameResource;
        $this->goalResource = $goalResource;
        $this->playerResource = $playerResource;
        $this->tournamentResource = $tournamentResource;
        $this->knockoutResource = $knockoutResource;
        $this->parameterParser = new TournamentParametersParser();
    }

    public function createTournament(ServerRequestInterface $request, Response $response, $args)
    {
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);

        $params = $this->parameterParser->parseCreateTournament($request);
        $usecase = new CreateTournament($this->tournamentResource, $this->gameResource, $this->teamResource,
            $this->playerResource, $this->knockoutResource);
        $useCaseResponse = $usecase->execute($connectedUser, $params);
        return $response->withStatus(200, "OK");
    }

    public function fetchOneTournament(ServerRequestInterface $request, Response $response, $args)
    {
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);

        $tournamentId = $args['tournament_id'];
        $tournament = $this->tournamentResource->selectOne($tournamentId);
        if ($tournament) {
            return $response->withJson(BabyfootTournamentArrayMapper::transform($tournament));
        }

        return $response->withStatus(404, 'Tournament not found.');
    }

    public function startGame(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseStartGame($request);

        if (!$params->isValid()) {
            return $response->withStatus(400, 'Missing arguments. Arguments required: Game ID to start.');
        }

        $connectedUser = $request->getAttribute("auth_user", null);
        if (!$connectedUser) {
            return $response->withStatus(400, 'Failed to find connected user.');
        }

        $useCase = new StartPlannedGame($this->gameResource);
        $useCaseResponse = $useCase->execute($connectedUser, $params->getGameId());
        if ($useCaseResponse->isSuccess()) {
            return $response->withJSON(BabyfootGameArrayMapper::transform($useCaseResponse->getData()));
        }
        return $response->withStatus($useCaseResponse->getState(), $useCaseResponse->getMessage());
    }
}