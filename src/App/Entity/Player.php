<?php

namespace App\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="player")
 */
class Player
{

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $avatar;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $surname;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $login;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    private $token;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     * @var \DateTime
     */
    private $tokenExpire;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="organization_id", referencedColumnName="id")
     * @var Organization
     */
    protected $organization;

    /**
     * Player constructor.
     * @param int $id
     * @param string $avatar
     * @param string $name
     * @param $surname
     * @param string $login
     * @param string $password
     * @param Organization $organization
     * @param string $token
     * @param \DateTime $tokenExpire
     */
    public function __construct($id, $avatar, $name, $surname, $login, $password, Organization $organization, $token, \DateTime $tokenExpire)
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->name = $name;
        $this->surname = $surname;
        $this->login = $login;
        $this->password = $password;
        $this->organization = $organization;
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
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
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