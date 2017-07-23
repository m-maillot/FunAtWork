<?php

namespace App\Middleware;

use App\Entity\Player;
use App\Resource\PlayerResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Http\Response;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/20/17
 * Time: 10:19 PM
 */
class TokenAuth
{

    const SCOPE_ADMIN = 1;
    const SCOPE_LOGGED = 2;

    /**
     * @var PlayerResource
     */
    private $playerResource;

    /**
     * @var integer
     */
    private $scope;

    public function __construct(App $app, $scope)
    {
        $this->playerResource = new PlayerResource($app->getContainer()->get('em'));
        $this->scope = $scope;
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
        if ($this->scope == TokenAuth::SCOPE_ADMIN) {
            //Get the token sent from jquery
            $tokenAuth = $request->getHeader('Authorization-Admin');
            if (!$tokenAuth || sizeof($tokenAuth) == 0 || $tokenAuth[0] !== "AZUEJDOSeL87jk") {
                return $response->withStatus(401, "Token not valid, admin access denied");
            }
            return $next($request->withAttribute('user', new Player(0, "", "Admin", "Admin", "Admin", "", "", new \DateTime(0))), $response);
        }

        //Get the token sent from jquery
        $tokenAuth = $request->getHeader('Authorization');
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
        return $next($request->withAttribute('user', $player), $response);
    }
}