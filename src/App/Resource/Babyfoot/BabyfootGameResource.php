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
     * @param int $organizationId
     * @param int $limit
     * @param bool $desc
     * @return BabyfootGame[]
     */
    public function selectWithoutTournament($organizationId, $limit = null, $desc = true)
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGame')->findBy(
            array('organization' => $organizationId, 'tournament' => null),
            array('startedDate' => ($desc) ? 'DESC' : 'ASC'), $limit);
    }

    /**
     * @param int $organizationId
     * @param int $gameId
     * @return BabyfootGame|null
     */
    public function selectOne($organizationId, $gameId)
    {
        /**
         * @var $game BabyfootGame|null
         */
        $game = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGame')->findOneBy(
            array('id' => $gameId, 'organization' => $organizationId)
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