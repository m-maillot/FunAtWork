<?php

namespace App\Resource\Babyfoot;

use App\AbstractResource;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Player;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Handle database access to babyfoot team table
 * @package App\Resource
 */
class BabyfootTeamResource extends AbstractResource
{
    /**
     * @return BabyfootTeam[]
     */
    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootTeam')->findAll();
    }

    /**
     * @param int $teamId
     * @return BabyfootTeam
     * @throws \Exception
     */
    public function selectOne($teamId)
    {
        /**
         * @var $team BabyfootTeam|null
         */
        $team = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootTeam')->findOneBy(
            array('id' => $teamId)
        );
        if ($team) {
            return $team;
        }
        throw new \Exception('Team not found');
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return BabyfootTeam|null
     */
    public function selectByPlayers(Player $player1, Player $player2)
    {
        /**
         * @var $qb QueryBuilder
         */
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')
            ->from('App\Entity\Babyfoot\BabyfootTeam', 't')
            ->where('t.playerAttack = ?1')
            ->andWhere('t.playerDefense = ?2');

        $qb->setParameters(array(1 => $player1, 2 => $player2));
        $query = $qb->getQuery();

        /**
         * @var $team array|null
         */
        $team = $query->getResult();
        if ($team && sizeof($team) == 1) {
            return $team[0];
        }
        return null;
    }

    /**
     * @param BabyfootTeam $team
     * @return BabyfootTeam
     */
    public function create(BabyfootTeam $team)
    {
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        return $team;
    }
}