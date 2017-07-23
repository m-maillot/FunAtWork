<?php

namespace App\Action\Player;

/**
 * Class PlayerWSParams
 * @package App\Action\Player
 */
class AddPlayerWSParams
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $surname;
    /**
     * @var string
     */
    private $avatar;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;

    /**
     * AddPlayerWSParams constructor.
     * @param string $name
     * @param string $surname
     * @param string $avatar
     * @param string $login
     * @param string $password
     */
    public function __construct($name, $surname, $avatar, $login, $password)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->avatar = $avatar;
        $this->login = $login;
        $this->password = $password;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed
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
        return $this->name && $this->avatar;
    }
}