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
        $surname = $request->getParsedBody()['surname'];
        $avatar = $request->getParsedBody()['avatar'];
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];
        return new AddPlayerWSParams($name, $surname, $avatar, $login, $password);
    }

    /**
     * @param ServerRequestInterface $request
     * @return SigninPlayerWSParams
     */
    public function parseSignin(ServerRequestInterface $request)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];
        return new SigninPlayerWSParams($login, $password);
    }
}