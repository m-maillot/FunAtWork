<?php

namespace App\Action;

use App\Entity\Player;
use App\Resource\PlayerResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class PlayerAction
{

    private $playerResource;

    public function __construct(PlayerResource $playerResource)
    {
        $this->playerResource = $playerResource;
    }

    public function fetch(ServerRequestInterface $request, Response $response, $args)
    {
        $players = $this->playerResource->select();
        return $response->withJSON($players);
    }

    public function fetchOne(ServerRequestInterface $request, Response $response, $args)
    {
        try {
            $player = $this->playerResource->selectOne($args['player_id']);
            return $response->withJSON($player);
        } catch (\Exception $e) {
            return $response->withStatus(404, $e->getMessage());
        }
    }

    public function create(ServerRequestInterface $request, Response $response, $args)
    {
        $name = $request->getParsedBody()['name'];
        $avatar = $request->getParsedBody()['avatar'];
        $player = $this->playerResource->create(new Player(0, $avatar, $name));
        if ($player) {
            return $response->withJSON($player);
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: name and avatar.');
    }
}