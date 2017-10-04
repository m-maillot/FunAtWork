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
        $data = array();
        $data['id'] = $game->getId();
        $data['status'] = $game->getStatus();
        if ($game->getRedTeam()) {
            $data['redTeam'] = BabyfootTeamArrayMapper::transform($game->getRedTeam());
        }
        if ($game->getBlueTeam()) {
            $data['blueTeam'] = BabyfootTeamArrayMapper::transform($game->getBlueTeam());
        }
        if ($game->getStatus() > BabyfootGame::GAME_PLANNED) {
            $data['redTeamGoal'] = self::computeGoals($game, $game->getRedTeam());
            $data['blueTeamGoal'] = self::computeGoals($game, $game->getBlueTeam());
        }
        $data['goals'] = BabyfootGoalArrayMapper::transforms($game->getGoals());
        if ($game->getStartedDate() != null) {
            $data['startedDate'] = $game->getStartedDate()->format(\DateTime::ISO8601);
        }
        if ($game->getPlannedDate() != null) {
            $data['plannedDate'] = $game->getPlannedDate()->format(\DateTime::ISO8601);
        }
        if ($game->getEndedDate() != null) {
            $data['endedDate'] = $game->getEndedDate()->format(\DateTime::ISO8601);
        }
        return $data;
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
    public static function computeGoals(BabyfootGame $game, BabyfootTeam $team = null)
    {
        if ($team == null) {
            return 0;
        }
        /**
         * @var int
         */
        $goals = 0;
        foreach ($game->getGoals() as $goal) {
            if (self::isGoalTeam($team, $goal->getStriker())) {
                if (!$goal->isGamelle()) {
                    $goals++;
                }
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