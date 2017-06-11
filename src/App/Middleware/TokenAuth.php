<?php

namespace App\Middleware;

use App\Resource\PlayerResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/20/17
 * Time: 10:19 PM
 */
class TokenAuth
{

    private $playerResource;

    public function __construct(PlayerResource $playerResource)
    {
        $this->playerResource = $playerResource;
    }

    /**
     * Check if user is auth and have access to route
     *
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        //Get the token sent from jquery
        $tokenAuth = $request->getHeader('Authorization');
        $path = $request->getUri()->getPath();
        if ($path == '/api/v1/signin') {
            return $next($request, $response);
        }

        if (!$tokenAuth || sizeof($tokenAuth) == 0) {
            return $response->withStatus(401, "Missing token");
        }
        $player = $this->playerResource->selectOneByToken($tokenAuth[0]);
        if (!$player) {
            return $response->withStatus(401, "Invalid token");
        }
        $currentDate = new \DateTime();
        if ($player->getTokenExpire()->getTimestamp() < $currentDate->getTimestamp()) {
            return $response->withStatus(401, "Token validity expired");
        }
        // Make it available for the controller
        $request->withAttribute("auth_user", $player);
        //Update token's expiration
        $interval = new \DateInterval('P1M');
        $currentDate->add($interval);
        $player->setTokenExpire($currentDate);
        $this->playerResource->update($player);
        //Continue with execution
        return $next($request, $response);
    }
}