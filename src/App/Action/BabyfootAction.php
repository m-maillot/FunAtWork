<?php

namespace App\Action;

use App\Action\Babyfoot\GameParametersParser;
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
use App\Resource\PlayerResource;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Perform action on babyfoot : start a game, goal, finish a game
 *
 * @package App\Action
 */
class BabyfootAction
{

    private $logger;
    private $teamResource;
    private $gameResource;
    private $goalResource;
    private $playerResource;
    private $parameterParser;

    public function __construct(Logger $logger, BabyfootTeamResource $teamResource, BabyfootGameResource $gameResource,
                                BabyfootGoalResource $goalResource, PlayerResource $playerResource)
    {
        $this->logger = $logger;
        $this->teamResource = $teamResource;
        $this->gameResource = $gameResource;
        $this->goalResource = $goalResource;
        $this->playerResource = $playerResource;
        $this->parameterParser = new GameParametersParser();
    }

    public function fetchGames(ServerRequestInterface $request, Response $response, $args)
    {
        $limit = $request->getQueryParam('limit', null);
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);
        $games = $this->gameResource->select($connectedUser->getOrganization()->getId(), $limit);
        return $response->withJSON(BabyfootGameArrayMapper::transforms($games));
    }

    public function fetchOneGame(ServerRequestInterface $request, Response $response, $args)
    {
        $gameId = $args['game_id'];
        /**
         * @var $connectedUser Player
         */
        $connectedUser = $request->getAttribute("auth_user", null);
        $game = $this->gameResource->selectOne($connectedUser->getOrganization()->getId(), $gameId);
        if ($game) {
            return $response->withJson(BabyfootGameArrayMapper::transform($game));
        }

        return $response->withStatus(404, 'Game not found.');
    }

    public function startGame(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseCreateGame($request);

        if (!$params->isValid()) {
            return $response->withStatus(400, 'Missing arguments. Arguments required: Blue players, Red players identifier.');
        }

        $connectedUser = $request->getAttribute("auth_user", null);
        if (!$connectedUser) {
            return $response->withStatus(400, 'Failed to find connected user.');
        }

        $useCase = new StartNewGame($this->teamResource, $this->gameResource, $this->playerResource);
        $useCaseResponse = $useCase->execute($connectedUser, $params->getBluePlayerAttackId(), $params->getBluePlayerDefenseId(),
            $params->getRedPlayerAttackId(), $params->getRedPlayerDefenseId());
        if ($useCaseResponse->isSuccess()) {
            return $response->withJSON(BabyfootGameArrayMapper::transform($useCaseResponse->getData()));
        }
        return $response->withStatus($useCaseResponse->getState(), $useCaseResponse->getMessage());
    }

    public function gameOver(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseGameOver($request);
        if (!$params->isValid()) {
            return $response->withStatus(400, 'Missing arguments. Arguments required: Game identifier.');
        }

        $connectedUser = $request->getAttribute("auth_user", null);
        if (!$connectedUser) {
            return $response->withStatus(400, 'Failed to find connected user.');
        }

        // TODO Check connected user is the creator or a player

        $useCase = new GameOver($this->gameResource);
        $useCaseResp = $useCase->execute($connectedUser, $params->getGameId(), $params->isCanceled());
        if ($useCaseResp->isSuccess()) {
            return $response->withJson(BabyfootGameArrayMapper::transform($useCaseResp->getData()));
        }
        return $response->withStatus($useCaseResp->getState(), $useCaseResp->getMessage());
    }

    public function addGoal(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseAddGoal($request);
        if (!$params->isValid()) {
            return $response->withStatus(400, 'Missing arguments. Arguments required: Game identifier, Player identifier (striker) and position.');
        }

        $connectedUser = $request->getAttribute("auth_user", null);
        if (!$connectedUser) {
            return $response->withStatus(400, 'Failed to find connected user.');
        }

        // TODO Check connected user is the creator or a player

        $useCase = new AddGoal($this->goalResource, $this->gameResource, $this->playerResource);
        $responseUseCase = $useCase->execute($connectedUser, $params->getGameId(), $params->getStrikerId(),
            $params->getPosition(), $params->isGamelle());
        if ($responseUseCase->isSuccess()) {
            return $response->withJson(BabyfootGameArrayMapper::transform($responseUseCase->getData()));
        }
        return $response->withStatus($responseUseCase->getState(), $responseUseCase->getMessage());
    }

    public function computeTeamStats(ServerRequestInterface $request, Response $response, $args)
    {
        $useCase = new ComputeTeamStats($this->gameResource);
        $responseUseCase = $useCase->execute();
        if ($responseUseCase->isSuccess()) {
            return $response->withJson(TeamStatsMapper::transform($responseUseCase->getData()));
        }
    }

    public function computePlayerStats(ServerRequestInterface $request, Response $response, $args)
    {
        $useCase = new ComputePlayerStats($this->gameResource);
        $responseUseCase = $useCase->execute();
        if ($responseUseCase->isSuccess()) {
            return $response->withJson(PlayerStatsMapper::transform($responseUseCase->getData()));
        }
    }

    public function computeUniquePlayerStats(ServerRequestInterface $request, Response $response, $args)
    {
        $useCase = new ComputeUniquePlayerStats($this->gameResource, $args['player_id']);
        $responseUseCase = $useCase->execute();
        if ($responseUseCase->isSuccess()) {
            return $response->withJson(PlayerStatsMapper::transformStatWithHistory($responseUseCase->getData()));
        }
    }
}