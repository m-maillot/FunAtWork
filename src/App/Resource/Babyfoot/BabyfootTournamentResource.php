<?php

namespace App\Resource\Babyfoot;

use App\AbstractResource;
use App\Entity\Babyfoot\BabyfootTournament;

/**
 * Handle access to babyfoot tournament table
 * @package App\Resource\Babyfoot
 */
class BabyfootTournamentResource extends AbstractResource
{

    /**
     * @param int $organizationId
     * @param int $limit
     * @param bool $desc
     * @return BabyfootTournament[]
     */
    public function select($organizationId, $limit = null, $desc = true)
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootTournament')->findBy(
            array('organization' => $organizationId),
            array('startedDate' => ($desc) ? 'DESC' : 'ASC'), $limit);
    }

    /**
     * @param int $tournamentId
     * @return BabyfootTournament|null
     */
    public function selectOne($tournamentId)
    {
        /**
         * @var $tournament BabyfootTournament|null
         */
        $tournament = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootTournament')->findOneBy(
            array('id' => $tournamentId)
        );
        return $tournament;
    }

    /**
     * @return BabyfootTournament|null
     */
    public function selectCurrent()
    {
        /**
         * @var $tournament BabyfootTournament|null
         */
        $tournament = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootTournament')->findOneBy(
            array(),
            array('id' => 'DESC')
        );
        return $tournament;
    }

    /**
     * @param BabyfootTournament $tournament
     * @return BabyfootTournament
     */
    public function createOrUpdate(BabyfootTournament $tournament)
    {
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
        return $tournament;
    }
}