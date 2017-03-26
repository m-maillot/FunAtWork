<?php

namespace App\Action;

use App\Action\Babyfoot\GameParametersParser;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGoal;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Babyfoot\Mapper\BabyfootGoalArrayMapper;
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
        $games = $this->gameResource->select();
        // Hack to retrieves goals
        foreach ($games as $game) {
            $this->logger->info("Get goals for game " . $game->getId());
            $goals = $this->goalResource->selectForGame($game);
            if ($goals) {
                $this->logger->info("Goals found ". $goals);
            }
            $game->setGoals($goals);
        }
        return $response->withJSON(BabyfootGameArrayMapper::transforms($games));
    }

    public function fetchOneGames(ServerRequestInterface $request, Response $response, $args)
    {
        $gameId = $this->parameterParser->parseFetchOneGame($request);
        $game = $this->gameResource->selectOne($gameId);
        if ($game) {
            return $response->withJson(BabyfootGameArrayMapper::transform($game));
        }

        return $response->withStatus(400, 'Game not found.');
    }

    public function startGame(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseCreateGame($request);

        if ($params->isValid()) {
            $bluePlayerAttack = $this->playerResource->selectOne($params->getBluePlayerAttackId());
            $bluePlayerDefense = $this->playerResource->selectOne($params->getBluePlayerDefenseId());
            $redPlayerAttack = $this->playerResource->selectOne($params->getRedPlayerAttackId());
            $redPlayerDefense = $this->playerResource->selectOne($params->getRedPlayerDefenseId());

            if ($bluePlayerAttack && $bluePlayerDefense && $redPlayerDefense && $redPlayerAttack) {
                // Check team is already exist, otherwise create a new one
                $blueTeam = $this->teamResource->selectByPlayers($bluePlayerAttack, $bluePlayerDefense);
                $redTeam = $this->teamResource->selectByPlayers($redPlayerAttack, $redPlayerDefense);
                if (!$blueTeam) {
                    $blueTeam = $this->teamResource->create(new BabyfootTeam(0, $bluePlayerAttack, $bluePlayerDefense));
                }
                if (!$redTeam) {
                    $redTeam = $this->teamResource->create(new BabyfootTeam(0, $redPlayerAttack, $redPlayerDefense));
                }

                if ($blueTeam && $redTeam) {
                    // Create the game
                    $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, $blueTeam, $redTeam, new \DateTime(), new \DateTime());
                    $game = $this->gameResource->createOrUpdate($game);
                    return $response->withJSON(BabyfootGameArrayMapper::transform($game));
                }
                return $response->withStatus(400, 'Failed to create team with players ' . $bluePlayerAttack->getName() . '-' . $bluePlayerDefense->getName() . ' vs ' . $redPlayerAttack->getName() . '-' . $redPlayerDefense->getName() . '.');
            }
            return $response->withStatus(400, 'Player not found.');
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: Blue players, Red players identifier.');
    }

    public function gameOver(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseGameOver($request);
        if ($params->isValid()) {
            $game = $this->gameResource->selectOne($params->getGameId());
            if ($game) {
                if ($game->getStatus() === BabyfootGame::GAME_STARTED) {
                    $game->setStatus($params->isCanceled() ? BabyfootGame::GAME_CANCELED : BabyfootGame::GAME_OVER);
                    $game = $this->gameResource->createOrUpdate($game);
                    return $response->withJSON(BabyfootGameArrayMapper::transform($game));
                }
                return $response->withStatus(400, 'Game is already over.');
            }
            return $response->withStatus(404, 'Game not found.');
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: Game identifier.');
    }

    public function addGoal(ServerRequestInterface $request, Response $response, $args)
    {
        $params = $this->parameterParser->parseAddGoal($request);

        if ($params->isValid()) {
            $game = $this->gameResource->selectOne($params->getGameId());
            if ($game) {
                if ($game->getStatus() === BabyfootGame::GAME_STARTED) {
                    $player = $this->playerResource->selectOne($params->getStrikerId());
                    if ($player) {
                        if ($this->checkPlayerId($game, $params->getStrikerId())) {
                            $goal = new BabyfootGoal(0, new \DateTime(), $player, $params->getPosition(), $params->isGamelle(), $game);
                            $goal = $this->goalResource->create($goal);
                            // TODO Return the game with new score ?
                            return $response->withJSON(BabyfootGoalArrayMapper::transform($goal));
                        }
                        return $response->withStatus(400, 'Player not found in this game');
                    }
                    return $response->withStatus(400, 'Player not found');
                }
                return $response->withStatus(400, 'Game is over.');
            }
            return $response->withStatus(400, 'Game not found.');
        }

        return $response->withStatus(400, 'Missing arguments. Arguments required: Game identifier, Player identifier (striker) and position.');
    }

    private
    function checkPlayerId(BabyfootGame $game, $strikerId)
    {
        return $game->getRedTeam()->getPlayerAttack()->getId() == $strikerId
            || $game->getRedTeam()->getPlayerDefense()->getId() == $strikerId
            || $game->getBlueTeam()->getPlayerAttack()->getId() == $strikerId
            || $game->getBlueTeam()->getPlayerDefense()->getId() == $strikerId;
    }
}