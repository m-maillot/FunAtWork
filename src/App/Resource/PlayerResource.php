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
     * @return Player[]
     */
    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findAll();
    }

    /**
     * @param int $playerId
     * @return Player|null|object
     */
    public function selectOne($playerId)
    {
        return $this->entityManager->getRepository('App\Entity\Player')->findOneBy(
            array('id' => $playerId)
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
}