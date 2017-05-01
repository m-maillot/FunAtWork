<?php

namespace App\Entity\Babyfoot\Mapper;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Player;

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
     * Compute the count goal made by the team for the game
     *
     * @param BabyfootGame $game
     * @param BabyfootTeam $team
     * @return int
     */
    public static function computeGoals(BabyfootGame $game, BabyfootTeam $team)
    {
        /**
         * @var int
         */
        $goals = 0;
        foreach ($game->getGoals() as $goal) {
            if (self::isGoalTeam($team, $goal->getStriker())
                && !$goal->isGamelle()
            ) {
                $goals++;
            } else if ($goal->isGamelle()) {
                $goals--;
            }
        }
        return $goals;
    }

    /**
     * Detect if the goal has been made by the team
     * @param BabyfootTeam $team
     * @param Player $player
     * @return bool
     */
    public static function isGoalTeam(BabyfootTeam $team, Player $player)
    {
        return $player->getId() == $team->getPlayerAttack()->getId()
            || $player->getId() == $team->getPlayerDefense()->getId();
    }
}