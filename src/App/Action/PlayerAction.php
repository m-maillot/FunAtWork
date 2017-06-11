<?php

namespace App\Action;

use App\Action\Player\PlayerParametersParser;
use App\Entity\Mapper\PlayerArrayMapper;
use App\Entity\Player;
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
}