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
     * @return AddPlayerWSParams
     */
    public function parse(ServerRequestInterface $request)
    {
        $name = $request->getParsedBody()['name'];
        $avatar = $request->getParsedBody()['avatar'];
        return new AddPlayerWSParams($name, $avatar);
    }

    /**
     * @param ServerRequestInterface $request
     * @return SigninPlayerWSParams
     */
    public function parseSignin(ServerRequestInterface $request){
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];
        return new SigninPlayerWSParams($login, $password);
    }
}