<?php

namespace App\Resource\Babyfoot;

use App\AbstractResource;
use App\Entity\Babyfoot\BabyfootGameKnockout;

/**
 * Resource to access table babyfoot game
 * @package App\Resource
 */
class BabyfootGameKnockoutResource extends AbstractResource
{
    /**
     * @param int $tournamentId
     * @param int $limit
     * @param bool $desc
     * @return BabyfootGameKnockout[]
     */
    public function select($tournamentId, $limit = null, $desc = true)
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGameKnockout')->findBy(
            array('tournament' => $tournamentId),
            array('startedDate' => ($desc) ? 'DESC' : 'ASC'), $limit);
    }

    /**
     * @param int $gameId
     * @return BabyfootGameKnockout|null
     */
    public function selectOne($gameId)
    {
        /**
         * @var $game BabyfootGameKnockout|null
         */
        $game = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGameKnockout')->findOneBy(
            array('id' => $gameId)
        );
        return $game;
    }

    /**
     * @param BabyfootGameKnockout $game
     * @return BabyfootGameKnockout
     */
    public function createOrUpdate(BabyfootGameKnockout $game)
    {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
        return $game;
    }
}