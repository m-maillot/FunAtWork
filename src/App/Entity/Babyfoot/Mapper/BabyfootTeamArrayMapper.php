<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:37 AM
 */

namespace App\Entity\Babyfoot\Mapper;


use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Mapper\PlayerArrayMapper;

class BabyfootTeamArrayMapper
{
    public static function transform(BabyfootTeam $team)
    {
        return array(
            'id' => $team->getId(),
            'attackPlayer' => PlayerArrayMapper::transform($team->getPlayerAttack()),
            'defensePlayer' => PlayerArrayMapper::transform($team->getPlayerDefense())
        );
    }

    /**
     * @param $teams BabyfootTeam[]
     * @return array
     */
    public static function transforms($teams)
    {
        return array_map(
            function ($team) {
                return self::transform($team);
            },
            $teams
        );
    }
}