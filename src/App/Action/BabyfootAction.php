<?php

namespace App\Action;

use App\Action\Babyfoot\GameParametersParser;
use App\Action\UseCase\AddGoal;
use App\Action\UseCase\GameOver;
use App\Action\UseCase\StartNewGame;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
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
        $limit = null;
        if (array_key_exists("limit", $args)) {
            $limit = $args['limit'];
        }
        $games = $this->gameResource->select($limit);
        return $response->withJSON(BabyfootGameArrayMapper::transforms($games));
    }

    public function fetchOneGame(ServerRequestInterface $request, Response $response, $args)
    {
        $gameId = $args['game_id'];
        $game = $this->gameResource->selectOne($gameId);
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

        $useCase = new StartNewGame($this->teamResource, $this->gameResource, $this->playerResource);
        $useCaseResponse = $useCase->execute($params->getBluePlayerAttackId(), $params->getBluePlayerDefenseId(),
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
        $useCase = new GameOver($this->gameResource);
        $useCaseResp = $useCase->execute($params->getGameId(), $params->isCanceled());
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

        $useCase = new AddGoal($this->goalResource, $this->gameResource, $this->playerResource);
        $responseUseCase = $useCase->execute($params->getGameId(), $params->getStrikerId(), $params->getPosition(), $params->isGamelle());
        if ($responseUseCase->isSuccess()) {
            return $response->withJson(BabyfootGameArrayMapper::transform($responseUseCase->getData()));
        }
        return $response->withStatus($responseUseCase->getState(), $responseUseCase->getMessage());
    }
}