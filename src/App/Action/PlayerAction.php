<?php

namespace App\Action;

use App\Action\Player\PlayerParametersParser;
use App\Entity\Mapper\PlayerArrayMapper;
use App\Entity\Player;
use App\Resource\OrganizationResource;
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
    private $organizationResource;
    private $parameterParser;

    public function __construct(PlayerResource $playerResource, OrganizationResource $organizationResource)
    {
        $this->playerResource = $playerResource;
        $this->organizationResource = $organizationResource;
        $this->parameterParser = new PlayerParametersParser();
    }

    public function fetch(ServerRequestInterface $request, Response $response, $args)
    {
        /**
         * @var Player $connectedUser
         */
        $connectedUser = $request->getAttribute("auth_user", null);
        $players = $this->playerResource->select($connectedUser->getOrganization()->getId());
        return $response->withJSON(PlayerArrayMapper::transforms($players));
    }

    public function fetchOne(ServerRequestInterface $request, Response $response, $args)
    {
        /**
         * @var Player $connectedUser
         */
        $connectedUser = $request->getAttribute("auth_user", null);
        $player = $this->playerResource->selectOne($args['player_id'], $connectedUser->getOrganization()->getId());
        if ($player) {
            return $response->withJSON(PlayerArrayMapper::transform($player));
        }
        return $response->withStatus(404, "Player not found");
    }

    public function create(ServerRequestInterface $request, Response $response, $args)
    {
        $param = $this->parameterParser->parse($request);
        if ($param->isValid()) {
            $organization = $this->organizationResource->selectOne($param->getGroupId());
            if ($organization) {
                $player = $this->playerResource->create(new Player(0, $param->getAvatar(), $param->getName(), $param->getSurname(),
                    $param->getLogin(), $param->getPassword(), $organization, "", new \DateTime()));
                if ($player) {
                    return $response->withJSON(PlayerArrayMapper::transform($player));
                } else {
                    return $response->withStatus(500, 'Failed to create player in database.');
                }
            } else {
                return $response->withStatus(400, 'Unknown organization');
            }
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: name and avatar.');
    }

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
        if ($player->getTokenExpire() > new \DateTime()) {
            // Token is valid, don't generate a new one
            return $response->withJson(array('player' => PlayerArrayMapper::transform($player), 'token' => $player->getToken(), 'expire_at' => $player->getTokenExpire()->getTimestamp()));
        }
        $generatedToken = bin2hex(openssl_random_pseudo_bytes(8));
        $tokenExpiration = new \DateTime();
        $interval = new \DateInterval('P6M');
        $tokenExpiration->add($interval);
        $player->setToken($generatedToken);
        $player->setTokenExpire($tokenExpiration);

        $this->playerResource->update($player);
        return $response->withJson(array('player' => PlayerArrayMapper::transform($player), 'token' => $player->getToken(), 'expire_at' => $player->getTokenExpire()->getTimestamp()));
    }
}