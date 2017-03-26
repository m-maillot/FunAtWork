<?php

namespace App\Resource\Babyfoot;

use App\AbstractResource;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootGoal;

/**
 * Resource to access table babyfoot goal
 * @package App\Resource
 */
class BabyfootGoalResource extends AbstractResource
{

    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGoal')->findAll();
    }

    public function selectOne($goalId)
    {
        $goal = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGoal')->findOneBy(
            array('id' => $goalId)
        );
        return $goal;
    }

    public function selectForGame(BabyfootGame $game)
    {
        /**
         * @var $game BabyfootGoal|null
         */
        $goals = $this->entityManager->getRepository('App\Entity\Babyfoot\BabyfootGoal')->findBy(
            array('game' => $game)
        );
        return $goals;
    }

    /**
     * @param BabyfootGoal $goal
     * @return BabyfootGoal
     */
    public function create(BabyfootGoal $goal)
    {
        $this->entityManager->persist($goal);
        $this->entityManager->flush();
        return $goal;
    }
}