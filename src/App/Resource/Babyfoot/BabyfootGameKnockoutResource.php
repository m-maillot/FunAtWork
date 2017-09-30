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
     * @param int $knockoutId
     * @return BabyfootGameKnockout|null
     */
    public function selectOne($knockoutId)
    {
        /**
         * @var $game BabyfootGameKnockout|null
         */
        $game = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGameKnockout')->findOneBy(
            array('id' => $knockoutId)
        );
        return $game;
    }

    /**
     * @param int $tournamentId
     * @param int $gameId
     * @param boolean $redPosition
     * @return BabyfootGameKnockout|null
     */
    public function selectNextGame($tournamentId, $gameId, $redPosition)
    {
        /**
         * @var $knockout BabyfootGameKnockout|null
         */
        $knockout = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGameKnockout')->findOneBy(
            array('tournament' => $tournamentId, 'game' => $gameId)
        );
        if ($knockout) {
            $winnerOf = $redPosition ? 'redWinnerOf' : 'blueWinnerOf';
            /**
             * @var $nextGame BabyfootGameKnockout|null
             */
            $nextGame = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGameKnockout')->findOneBy(
                array('tournament' => $tournamentId, $winnerOf => $knockout->getId())
            );
            return $nextGame;
        }
        return null;
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