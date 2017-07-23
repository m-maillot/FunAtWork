<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/21/17
 * Time: 8:05 AM
 */

namespace App\Action\Player;


class SigninPlayerWSParams
{

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * SigninPlayer constructor.
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->login && $this->password;
    }
}