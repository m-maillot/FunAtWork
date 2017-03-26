<?php

namespace App\Action\Player;

/**
 * Class PlayerWSParams
 * @package App\Action\Player
 */
class PlayerWSParams
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $avatar;

    /**
     * PlayerWSParams constructor.
     * @param string $name
     * @param string $avatar
     */
    public function __construct($name, $avatar)
    {
        $this->name = $name;
        $this->avatar = $avatar;
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
     * @return bool
     */
    public function isValid()
    {
        return $this->name && $this->avatar;
    }
}