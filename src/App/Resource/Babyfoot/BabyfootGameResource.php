<?php

namespace App\Resource\Babyfoot;

use App\AbstractResource;
use App\Entity\Babyfoot\BabyfootGame;

/**
 * Resource to access table babyfoot game
 * @package App\Resource
 */
class BabyfootGameResource extends AbstractResource
{
    /**
     * @return BabyfootGame[]
     */
    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGame')->findAll();
    }

    /**
     * @param int $gameId
     * @return BabyfootGame|null
     */
    public function selectOne($gameId)
    {
        /**
         * @var $game BabyfootGame|null
         */
        $game = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGame')->findOneBy(
            array('id' => $gameId)
        );
        return $game;
    }

    /**
     * @param BabyfootGame $game
     * @return BabyfootGame
     */
    public function createOrUpdate(BabyfootGame $game)
    {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
        return $game;
    }
}