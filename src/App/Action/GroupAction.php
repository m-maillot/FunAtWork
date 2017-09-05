<?php

namespace App\Action;

use App\Action\Group\GroupParametersParser;
use App\Entity\Group;
use App\Entity\Mapper\GroupArrayMapper;
use App\Resource\GroupResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Perform action on player as add/edit/delete/view
 *
 * @package App\Action
 */
class GroupAction
{

    private $groupResource;
    private $parameterParser;

    public function __construct(GroupResource $groupResource)
    {
        $this->groupResource = $groupResource;
        $this->parameterParser = new GroupParametersParser();
    }

    public function fetch(ServerRequestInterface $request, Response $response, $args)
    {
        $groups = $this->groupResource->select();
        return $response->withJSON(GroupArrayMapper::transforms($groups));
    }

    public function fetchOne(ServerRequestInterface $request, Response $response, $args)
    {
        $group = $this->groupResource->selectOne($args['group_id']);
        if ($group) {
            return $response->withJSON(GroupArrayMapper::transform($group));
        }
        return $response->withStatus(404, "Player not found");
    }

    public function create(ServerRequestInterface $request, Response $response, $args)
    {
        $param = $this->parameterParser->parse($request);
        if ($param->isValid()) {
            $group = $this->groupResource->create(new Group(0, $param->getIcon(), $param->getName()));
            if ($group) {
                return $response->withJSON(GroupArrayMapper::transform($group));
            } else {
                return $response->withStatus(500, 'Failed to create group in database.');
            }
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: name.');
    }
}