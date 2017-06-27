<?php

namespace App\Action\UseCase\Model;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/25/17
 * Time: 9:57 PM
 */
class PlayerStatsMapper
{
    public static function transform($playerStats)
    {
        $data = array();
        /**
         * @var $value PlayerStats
         */
        foreach ($playerStats as $key => $value) {
            array_push($data, self::transformStat($value));
        }
        return $data;
    }

    public static function transformStat(PlayerStats $playerStats)
    {
        return [
            'id' => $playerStats->player->getId(),
            'avatar' => $playerStats->player->getAvatar(),
            'name' => $playerStats->player->getName(),
            'eloRanking' => $playerStats->eloRanking,
            'gamePlayed' => $playerStats->gamePlayed,
            'victory' => $playerStats->victory,
            'loose' => $playerStats->loose,
            'goalAverage' => $playerStats->teamGoalaverage
        ];
    }
}