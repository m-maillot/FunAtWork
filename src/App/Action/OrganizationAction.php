<?php

namespace App\Action;

use App\Action\Group\OrganizationParametersParser;
use App\Entity\Organization;
use App\Entity\Mapper\OrganizationArrayMapper;
use App\Resource\OrganizationResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Perform action on player as add/edit/delete/view
 *
 * @package App\Action
 */
class OrganizationAction
{

    private $organizationResource;
    private $parameterParser;

    public function __construct(OrganizationResource $organizationResource)
    {
        $this->organizationResource = $organizationResource;
        $this->parameterParser = new OrganizationParametersParser();
    }

    public function fetch(ServerRequestInterface $request, Response $response, $args)
    {
        $groups = $this->organizationResource->select();
        return $response->withJSON(OrganizationArrayMapper::transforms($groups));
    }

    public function fetchOne(ServerRequestInterface $request, Response $response, $args)
    {
        $group = $this->organizationResource->selectOne($args['group_id']);
        if ($group) {
            return $response->withJSON(OrganizationArrayMapper::transform($group));
        }
        return $response->withStatus(404, "Player not found");
    }

    public function create(ServerRequestInterface $request, Response $response, $args)
    {
        $param = $this->parameterParser->parse($request);
        if ($param->isValid()) {
            $group = $this->organizationResource->create(new Organization(0, $param->getIcon(), $param->getName()));
            if ($group) {
                return $response->withJSON(OrganizationArrayMapper::transform($group));
            } else {
                return $response->withStatus(500, 'Failed to create group in database.');
            }
        }
        return $response->withStatus(400, 'Missing arguments. Arguments required: name.');
    }
}