<?php

namespace App\Action;

use App\Action\Player\PlayerParametersParser;
use App\Entity\Mapper\PlayerArrayMapper;
use App\Resource\PlayerResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Perform action on player as add/edit/delete/view
 *
 * @package App\Action
 */
class PlayerAction
{

    private $playerResource;
    private $parameterParser;

    public function __construct(PlayerResource $playerResource)
    {
        $this->playerResource = $playerResource;
        $this->parameterParser = new PlayerParametersParser();
    }

    public function fetch(ServerRequestInterface $request, Response $response, $args)
    {
        $players = $this->playerResource->select();
        return $response->withJSON(PlayerArrayMapper::transforms($players));
    }

    public function fetchOne(ServerRequestInterface $request, Response $response, $args)
    {
        $player = $this->playerResource->selectOne($args['player_id']);
        if ($player) {
            return $response->withJSON(PlayerArrayMapper::transform($player));
        }
        return $response->withStatus(404, "Player not found");
    }

    /*
    // Create player API is disabled until add admin access
    public function create(ServerRequestInterface $request, Response $response, $args)
    {
        $param = $this->parameterParser->parse($request);
        if ($param->isValid()) {
            $player = $this->playerResource->create(new Player(0, $param->getAvatar(), $param->getName()));
            if ($player) {
                return $response->withJSON(PlayerArrayMapper::transform($player));
            } else {
                return $response->withStatus(500, 'Failed to create player in database.');
            }
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: name and avatar.');
    }
    */

    public function signin(ServerRequestInterface $request, Response $response, $args)
    {

        $params = $this->parameterParser->parseSignin($request);
        if (!$params->isValid()) {
            $response->withStatus(400, "Missing login or password");
        }

        $player = $this->playerResource->selectOneByLoginPassword($params->getLogin(), $params->getPassword());

        if (!$player) {
            $response->withStatus(404, "Player not found");
        }
        $generatedToken = bin2hex(openssl_random_pseudo_bytes(8));
        $tokenExpiration = new \DateTime();
        $interval = new \DateInterval('P1M');
        $tokenExpiration->add($interval);
        $player->setToken($generatedToken);
        $player->setTokenExpire($tokenExpiration);

        $this->playerResource->update($player);
        return $response->withJson(array('login' => $player->getLogin(), 'token' => $player->getToken(), 'expire_at' => $player->getTokenExpire()->getTimestamp()));
    }
}