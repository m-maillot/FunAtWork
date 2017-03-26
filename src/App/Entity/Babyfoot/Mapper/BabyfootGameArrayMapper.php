<?php

namespace App\Entity\Babyfoot\Mapper;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:36 AM
 */
class BabyfootGameArrayMapper
{
    public static function transform(BabyfootGame $game)
    {
        return [
            'id' => $game->getId(),
            'redTeam' => BabyfootTeamArrayMapper::transform($game->getRedTeam()),
            'redTeamGoal' => self::computeGoals($game, $game->getRedTeam()),
            'blueTeamGoal' => self::computeGoals($game, $game->getBlueTeam()),
            'blueTeam' => BabyfootTeamArrayMapper::transform($game->getBlueTeam()),
            'status' => $game->getStatus(),
            'goals' => BabyfootGoalArrayMapper::transforms($game->getGoals()),
            'started' => $game->getStartedDate()->getTimestamp(),
            'ended' => $game->getEndedDate()->getTimestamp()
        ];
    }

    /**
     * @param $games BabyfootGame[]
     * @return array
     */
    public static function transforms($games)
    {
        return array_map(
            function ($game) {
                return self::transform($game);
            },
            $games
        );
    }

    /**
     * @param BabyfootGame $game
     * @param BabyfootTeam $team
     * @return int
     */
    public static function computeGoals(BabyfootGame $game, BabyfootTeam $team)
    {
        $player1 = $team->getPlayerAttack()->getId();
        $player2 = $team->getPlayerDefense()->getId();
        $goals = 0;
        foreach ($game->getGoals() as $goal) {
            if ($goal->getStriker()->getId() == $player1
                || $goal->getStriker()->getId() == $player2
            ) {
                $goals++;
            }
        }
        return $goals;
    }
}