<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:41 AM
 */

namespace App\Entity\Babyfoot\Mapper;


use App\Entity\Babyfoot\BabyfootGoal;
use App\Entity\Mapper\PlayerArrayMapper;

class BabyfootGoalArrayMapper
{

    public static function transform(BabyfootGoal $goal)
    {
        return array(
            'id' => $goal->getId(),
            'stricker' => PlayerArrayMapper::transform($goal->getStriker()),
            'position' => $goal->getPosition()
        );
    }

    /**
     * @param $goals BabyfootGoal[]
     * @return array
     */
    public static function transforms($goals)
    {
        return array_map(
            function ($goal) {
                return self::transform($goal);
            },
            $goals
        );
    }
}