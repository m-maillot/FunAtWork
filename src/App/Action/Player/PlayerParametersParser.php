<?php

namespace App\Action\Player;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PlayerParametersParser
 * @package App\Action\Player
 */
class PlayerParametersParser
{

    /**
     * @param ServerRequestInterface $request
     * @return PlayerWSParams
     */
    public function parse(ServerRequestInterface $request)
    {
        $name = $request->getParsedBody()['name'];
        $avatar = $request->getParsedBody()['avatar'];
        return new PlayerWSParams($name, $avatar);
    }
}