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
use Doctrine\ORM\PersistentCollection;

class BabyfootGoalArrayMapper
{

    public static function transform(BabyfootGoal $goal)
    {
        return array(
            'id' => $goal->getId(),
            'date' => $goal->getGoalDate()->format(\DateTime::ISO8601),
            'striker' => PlayerArrayMapper::transform($goal->getStriker()),
            'position' => $goal->getPosition()
        );
    }

    /**
     * @param $goals mixed
     * @return array
     */
    public static function transforms($goals)
    {
        /**
         * @var $goalsArray BabyfootGoal[]
         */
        $goalsArray = $goals;
        if ($goals instanceof PersistentCollection) {
            /**
             * @var $goalsArray PersistentCollection
             */
            $tmp = $goals;
            $goalsArray = $tmp->toArray();
        }
        return array_map(
            function ($goal) {
                return self::transform($goal);
            },
            $goalsArray
        );
    }
}