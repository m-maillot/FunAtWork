<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $avatar;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $surname;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $login;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $tokenExpire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", fetch="EAGER")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @var Group
     */
    protected $group;

    /**
     * Player constructor.
     * @param int $id
     * @param string $avatar
     * @param string $name
     * @param $surname
     * @param string $login
     * @param string $password
     * @param string $token
     * @param \DateTime $tokenExpire
     */
    public function __construct($id, $avatar, $name, $surname, $login, $password, $token, \DateTime $tokenExpire)
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->name = $name;
        $this->surname = $surname;
        $this->login = $login;
        $this->password = $password;
        $this->token = $token;
        $this->tokenExpire = $tokenExpire;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
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
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getTokenExpire()
    {
        return $this->tokenExpire;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param \DateTime $tokenExpire
     */
    public function setTokenExpire($tokenExpire)
    {
        $this->tokenExpire = $tokenExpire;
    }
}