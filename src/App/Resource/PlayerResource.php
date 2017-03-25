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
     * @return array
     */
    public function select()
    {
        $players = $this->entityManager->getRepository('App\Entity\Player')->findAll();
        $players = array_map(
            function ($player) {
                return $player->expose();
            },
            $players
        );

        return $players;
    }

    /**
     * @param int $playerId
     * @return array
     * @throws \Exception
     */
    public function selectOne($playerId)
    {
        /**
         * @var Player
         */
        $player = $this->entityManager->getRepository('App\Entity\Player')->findOneBy(
            array('id' => $playerId)
        );
        if ($player) {
            return $player->expose();
        }
        throw new \Exception('Player not found');
    }

    public function create(Player $player)
    {
        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return $player->expose();
    }
}