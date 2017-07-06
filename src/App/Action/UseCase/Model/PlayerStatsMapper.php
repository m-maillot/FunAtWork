<?php

namespace App\Action\UseCase\Model;

use App\Entity\Mapper\PlayerArrayMapper;

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
            'player' => PlayerArrayMapper::transform($playerStats->player),
            'eloRanking' => $playerStats->getEloRanking(),
            'gamePlayed' => $playerStats->gamePlayed,
            'victory' => $playerStats->victory,
            'loose' => $playerStats->loose,
            'goalAverage' => $playerStats->goalAverage,
            'goals' => $playerStats->goals,
            'goalsByMatch' => $playerStats->goals / $playerStats->gamePlayed
        ];
    }

    public static function transformStatWithHistory(PlayerStats $playerStats)
    {
        return [
            'player' => PlayerArrayMapper::transform($playerStats->player),
            'eloRanking' => $playerStats->getEloRanking(),
            'gamePlayed' => $playerStats->gamePlayed,
            'victory' => $playerStats->victory,
            'loose' => $playerStats->loose,
            'goalAverage' => $playerStats->goalAverage,
            'goals' => $playerStats->goals,
            'matchs' => self::transformHistories($playerStats->matchsHistory)
        ];
    }

    /**
     * @param $histories MatchHistory[]
     * @return array
     */
    public static function transformHistories($histories)
    {
        $data = array();
        /**
         * @var $value PlayerStats
         */
        foreach ($histories as $history) {
            array_push($data, self::transformHistory($history));
        }
        return $data;
    }

    /**
     * @param $history MatchHistory
     * @return array
     */
    public static function transformHistory($history)
    {
        return [
            'redTeam' => [
                'player1' => PlayerArrayMapper::transform($history->getRedPlayer1()),
                'player1Elo' => $history->getRedPlayer1EloRanking(),
                'player2' => PlayerArrayMapper::transform($history->getRedPlayer2()),
                'player2Elo' => $history->getRedPlayer2EloRanking(),
                'elo' => $history->getRedTeamEloRanking(),
                'eloWin' => $history->getRedTeamEloRankingGain(),
                'expWin' => 1 / (1 + pow(10, (($history->getBlueTeamEloRanking() - $history->getRedTeamEloRanking()) / 400)))
            ],
            'blueTeam' => [
                'player1' => PlayerArrayMapper::transform($history->getBluePlayer1()),
                'player1Elo' => $history->getBluePlayer1EloRanking(),
                'player2' => PlayerArrayMapper::transform($history->getBluePlayer2()),
                'player2Elo' => $history->getBluePlayer2EloRanking(),
                'elo' => $history->getBlueTeamEloRanking(),
                'eloWin' => $history->getBlueTeamEloRankingGain(),
                'expWin' => 1 / (1 + pow(10, (($history->getRedTeamEloRanking() - $history->getBlueTeamEloRanking()) / 400)))
            ],
            'score' => [
                'red' => $history->getRedGoals(),
                'blue' => $history->getBlueGoals()
            ]
        ];
    }
}