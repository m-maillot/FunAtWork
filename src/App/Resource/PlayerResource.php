<?php

namespace App\Resource;

use App\AbstractResource;
use App\Entity\Player;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/24/17
 * Time: 10:45 PM
 */
class PlayerResource extends AbstractResource
{
    /**
     * @param int $organizationId
     * @return Player[]
     */
    public function select($organizationId)
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findBy(
            array('organization' => $organizationId),
            array('name' => 'ASC')
        );
    }

    /**
     * @param int $playerId
     * @param int $organizationId
     * @return Player|null|object
     */
    public function selectOne($playerId, $organizationId)
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findOneBy(
            array('id' => $playerId, 'organization' => $organizationId)
        );
    }

    /**
     * @param string $token
     * @return Player|null|object
     */
    public function selectOneByToken($token)
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findOneBy(
            array('token' => $token)
        );
    }

    /**
     * @param string $login
     * @param string $password
     * @return Player|null|object
     */
    public function selectOneByLoginPassword($login, $password)
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findOneBy(
            array('login' => $login, 'password' => $password)
        );
    }

    /**
     * @param Player $player
     * @return Player
     */
    public function create(Player $player)
    {
        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return $player;
    }

    /**
     * Update a player from DB
     * @param Player $player
     * @return Player
     */
    public function update(Player $player)
    {
        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return $player;
    }
}