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
     * @param int $limit
     * @return BabyfootGame[]
     */
    public function select($limit = null)
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGame')->findBy(array(), array('startedDate' => 'DESC'), $limit);
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