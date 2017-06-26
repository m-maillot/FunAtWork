<?php

namespace App\Action\UseCase\Model;

use App\Entity\Mapper\PlayerArrayMapper;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/25/17
 * Time: 9:57 PM
 */
class TeamStatsMapper
{
    public static function transform($teamStats)
    {
        $data = array();
        /**
         * @var $value TeamStats
         */
        foreach ($teamStats as $key => $value) {
            if ($value->gamePlayed > 5) {
                array_push($data, self::transformStat($value));
            }
        }
        return $data;
    }

    public static function transformStat(TeamStats $teamStats)
    {
        return [
            'id' => $teamStats->id,
            'player1' => PlayerArrayMapper::transform($teamStats->player1),
            'player2' => PlayerArrayMapper::transform($teamStats->player2),
            'eloRanking' => $teamStats->eloRanking,
            'gamePlayed' => $teamStats->gamePlayed,
            'victory' => $teamStats->victory,
            'loose' => $teamStats->loose,
            'goalAvg' => $teamStats->teamGoalAvg
        ];
    }
}