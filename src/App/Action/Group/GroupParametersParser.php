<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/5/17
 * Time: 1:53 PM
 */

namespace App\Action\Group;


use Psr\Http\Message\ServerRequestInterface;

class GroupParametersParser
{

    /**
     * @param ServerRequestInterface $request
     * @return AddGroupWSParams
     */
    public function parse(ServerRequestInterface $request)
    {
        $name = $request->getParsedBody()['name'];
        $icon = $request->getParsedBody()['icon'];
        return new AddGroupWSParams($name, $icon);
    }
}