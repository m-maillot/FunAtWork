<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/26/17
 * Time: 2:38 PM
 */

namespace App\Action\UseCase;


use App\Action\UseCase\Model\MatchHistory;
use App\Action\UseCase\Model\PlayerStats;
use App\Action\UseCase\Model\TeamStats;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Player;

class ComputeStats
{

    const RED_ATTACK = "redAttack";
    const RED_DEFENSE = "redDefense";
    const BLUE_ATTACK = "blueAttack";
    const BLUE_DEFENSE = "blueDefense";

    private $teamsStats = array();
    private $teamsStatsNotOrdered = array();
    /**
     * @var PlayerStats[]
     */
    private $playersStats = array();
    private $playersStatsNotOrdered = array();

    /**
     * @param $games BabyfootGame[]
     * @return boolean
     */
    public function compute($games)
    {
        foreach ($games as $game) {
            if ($game->getStatus() === BabyfootGame::GAME_OVER && $this->isValidGame($game)) {
                $redTeam = $this->initTeamStats($game->getRedTeam());
                $blueTeam = $this->initTeamStats($game->getBlueTeam());
                $redAttack = $this->initPlayerStats($game->getRedTeam()->getPlayerAttack());
                $redDefense = $this->initPlayerStats($game->getRedTeam()->getPlayerDefense());
                $blueAttack = $this->initPlayerStats($game->getBlueTeam()->getPlayerAttack());
                $blueDefense = $this->initPlayerStats($game->getBlueTeam()->getPlayerDefense());
                $players = array("redAttack" => $redAttack, "redDefense" => $redDefense, "blueAttack" => $blueAttack, "blueDefense" => $blueDefense);
                $this->setStatsVictoryLoose($game, $redTeam, $blueTeam, $players);
                $this->setStatsGamePlayed($redTeam, $blueTeam, $players);
                $this->setStatsTeamGoalAverage($game, $redTeam, $blueTeam, $players);
                $this->setStatsEloRanking($game, $redTeam, $blueTeam, $redAttack, $redDefense, $blueAttack, $blueDefense);
            }
        }

        usort($this->teamsStats, function ($a, $b) {

            /**
             * @var $a TeamStats
             * @var $b TeamStats
             */
            return $b->eloRanking * 100 - $a->eloRanking * 100;
        });

        usort($this->playersStats, function ($a, $b) {

            /**
             * @var $a PlayerStats
             * @var $b PlayerStats
             */
            return $b->getEloRanking() * 100 - $a->getEloRanking() * 100;
        });
        $i = 1;
        foreach ($this->playersStats as $playersStat) {
            $playersStat->rank = $i;
            $i++;
        }

        return true;
    }

    public function getTeamStats()
    {
        return $this->teamsStats;
    }

    public function getPlayerStats()
    {
        return $this->playersStats;
    }

    /**
     * @param $playerId int
     * @return PlayerStats|null
     */
    public function getPlayerStatsById($playerId)
    {
        foreach ($this->playersStats as $playersStat) {
            if ($playersStat->player->getId() == $playerId) {
                return $playersStat;
            }
        }
        return null;
    }

    private function isValidGame(BabyfootGame $game)
    {
        return $game->getBlueTeam()->getPlayerDefense()->getId() !== $game->getBlueTeam()->getPlayerAttack()->getId()
            && $game->getRedTeam()->getPlayerDefense()->getId() !== $game->getRedTeam()->getPlayerAttack()->getId();
    }

    /**
     * @param Player $player
     *
     * @return PlayerStats
     */
    private function initPlayerStats(Player $player)
    {
        $playerId = $player->getId();
        if ($this->playersStats[$playerId]) {
            return $this->playersStats[$playerId];
        }
        $playerStats = new PlayerStats();
        $playerStats->player = $player;
        $this->playersStats[$playerId] = $playerStats;
        $this->playersStatsNotOrdered[$playerId] = $playerStats;
        return $playerStats;
    }

    /**
     * @param BabyfootTeam $team
     * @return TeamStats
     */
    private function initTeamStats(BabyfootTeam $team)
    {
        $uniqueId = $this->getTeamUniqueId($team);
        if ($this->teamsStats[$uniqueId]) {
            return $this->teamsStats[$uniqueId];
        }
        $teamStats = new TeamStats();
        $teamStats->id = $uniqueId;
        $teamStats->player1 = $team->getPlayerAttack();
        $teamStats->player2 = $team->getPlayerDefense();
        $this->teamsStats[$uniqueId] = $teamStats;
        $this->teamsStatsNotOrdered[$uniqueId] = $teamStats;
        return $teamStats;
    }

    /**
     * @param BabyfootTeam $team
     * @return string
     */
    private function getTeamUniqueId(BabyfootTeam $team)
    {
        $attackId = $team->getPlayerAttack()->getId();
        $defenseId = $team->getPlayerDefense()->getId();
        return max($attackId, $defenseId) . "-" . min($attackId, $defenseId);
    }

    /**
     * @param BabyfootGame $game
     * @param TeamStats $redTeam
     * @param TeamStats $blueTeam
     * @param $players PlayerStats[]
     */
    private function setStatsVictoryLoose(BabyfootGame $game, TeamStats &$redTeam, TeamStats &$blueTeam, $players)
    {
        $redWinner = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam()) > BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());
        if ($redWinner) {
            $players[ComputeStats::RED_ATTACK]->victory++;
            $players[ComputeStats::RED_DEFENSE]->victory++;
            $players[ComputeStats::BLUE_ATTACK]->loose++;
            $players[ComputeStats::BLUE_DEFENSE]->loose++;

            $redTeam->victory++;
            $blueTeam->loose++;

            array_push($players[ComputeStats::RED_ATTACK]->gameSeries, 'W');
            array_push($players[ComputeStats::RED_DEFENSE]->gameSeries, 'W');
            array_push($players[ComputeStats::BLUE_ATTACK]->gameSeries, 'L');
            array_push($players[ComputeStats::BLUE_DEFENSE]->gameSeries, 'L');
        } else {
            $players[ComputeStats::RED_ATTACK]->loose++;
            $players[ComputeStats::RED_DEFENSE]->loose++;
            $players[ComputeStats::BLUE_ATTACK]->victory++;
            $players[ComputeStats::BLUE_DEFENSE]->victory++;

            $redTeam->loose++;
            $blueTeam->victory++;

            array_push($players[ComputeStats::RED_ATTACK]->gameSeries, 'L');
            array_push($players[ComputeStats::RED_DEFENSE]->gameSeries, 'L');
            array_push($players[ComputeStats::BLUE_ATTACK]->gameSeries, 'W');
            array_push($players[ComputeStats::BLUE_DEFENSE]->gameSeries, 'W');
        }
    }

    /**
     * @param TeamStats $redTeam
     * @param TeamStats $blueTeam
     * @param $players PlayerStats[]
     */
    private function setStatsGamePlayed(TeamStats &$redTeam, TeamStats &$blueTeam, $players)
    {
        $players[ComputeStats::RED_ATTACK]->gamePlayed++;
        $players[ComputeStats::RED_DEFENSE]->gamePlayed++;
        $players[ComputeStats::BLUE_ATTACK]->gamePlayed++;
        $players[ComputeStats::BLUE_DEFENSE]->gamePlayed++;

        $redTeam->gamePlayed++;
        $blueTeam->gamePlayed++;
    }

    /**
     * @param BabyfootGame $game
     * @param TeamStats $redTeam
     * @param TeamStats $blueTeam
     * @param $players PlayerStats[]
     */
    private function setStatsTeamGoalAverage(BabyfootGame $game, TeamStats &$redTeam, TeamStats &$blueTeam, $players)
    {
        $redGoal = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam());
        $blueGoal = BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());

        $players[ComputeStats::RED_ATTACK]->goalAverage += $redGoal;
        $players[ComputeStats::RED_ATTACK]->goalAverage -= $blueGoal;

        $players[ComputeStats::RED_ATTACK]->goals += $this->getGoalsForUser($game, $players[ComputeStats::RED_ATTACK]);

        $players[ComputeStats::RED_DEFENSE]->goalAverage += $redGoal;
        $players[ComputeStats::RED_DEFENSE]->goalAverage -= $blueGoal;
        $players[ComputeStats::RED_DEFENSE]->goals += $this->getGoalsForUser($game, $players[ComputeStats::RED_DEFENSE]);

        $players[ComputeStats::BLUE_ATTACK]->goalAverage -= $redGoal;
        $players[ComputeStats::BLUE_ATTACK]->goalAverage += $blueGoal;
        $players[ComputeStats::BLUE_ATTACK]->goals += $this->getGoalsForUser($game, $players[ComputeStats::BLUE_ATTACK]);
        $players[ComputeStats::BLUE_DEFENSE]->goalAverage -= $redGoal;
        $players[ComputeStats::BLUE_DEFENSE]->goalAverage += $blueGoal;
        $players[ComputeStats::BLUE_DEFENSE]->goals += $this->getGoalsForUser($game, $players[ComputeStats::BLUE_DEFENSE]);

        $redTeam->goalAverage += $redGoal;
        $redTeam->goalAverage -= $blueGoal;
        $blueTeam->goalAverage -= $redGoal;
        $blueTeam->goalAverage += $blueGoal;
    }

    private function getGoalsForUser(BabyfootGame $game, PlayerStats $playerStats)
    {
        $goals = 0;
        foreach ($game->getGoals() as $goal) {
            if ($goal->getStriker()->getId() === $playerStats->player->getId()) {
                $goals++;
            }
        }
        return $goals;
    }

    private function setStatsEloRanking(BabyfootGame $game, TeamStats $redTeam, TeamStats $blueTeam,
                                        PlayerStats $redPlayerAttack, PlayerStats $redPlayerDefense,
                                        PlayerStats $bluePlayerAttack, PlayerStats $bluePlayerDefense)
    {
        $redGoals = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam());
        $blueGoals = BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());
        $redWinner = $redGoals > $blueGoals;

        if ($redWinner) {
            $redWins = 1;
            $blueWins = 0;
        } else {
            $redWins = 0;
            $blueWins = 1;
        }

        $redTeamAvgEloRanking = ($redPlayerAttack->getEloRanking() + $redPlayerDefense->getEloRanking()) / 2;
        $blueTeamAvgEloRanking = ($bluePlayerAttack->getEloRanking() + $bluePlayerDefense->getEloRanking()) / 2;

        //Calculate Red and Blue Win Expectancy
        $RedWe = 1 / (1 + pow(10, (($blueTeamAvgEloRanking - $redTeamAvgEloRanking) / 400)));
        $BlueWe = 1 / (1 + pow(10, (($redTeamAvgEloRanking - $blueTeamAvgEloRanking) / 400)));

        $KFactor = 32;

        $redNewEloRanking = $redTeamAvgEloRanking + ($KFactor * ($redWins - $RedWe));
        $redRanking = +($redNewEloRanking - $redTeamAvgEloRanking);
        $redTeam->eloRanking = $redNewEloRanking;


        $blueNewEloRanking = $blueTeamAvgEloRanking + ($KFactor * ($blueWins - $BlueWe));
        $blueRanking = +($blueNewEloRanking - $blueTeamAvgEloRanking);
        $blueTeam->eloRanking = $blueNewEloRanking;


        $matchHistory = new MatchHistory($redPlayerAttack->player, $redPlayerAttack->getEloRanking(),
            $redPlayerDefense->player, $redPlayerDefense->getEloRanking(),
            $redTeamAvgEloRanking, $redRanking,
            $bluePlayerAttack->player, $bluePlayerAttack->getEloRanking(),
            $bluePlayerDefense->player, $bluePlayerDefense->getEloRanking(),
            $blueTeamAvgEloRanking, $blueRanking,
            $blueGoals, $redGoals);
        array_push($bluePlayerAttack->matchsHistory, $matchHistory);
        array_push($bluePlayerDefense->matchsHistory, $matchHistory);
        array_push($redPlayerAttack->matchsHistory, $matchHistory);
        array_push($redPlayerDefense->matchsHistory, $matchHistory);


        $redPlayerAttack->incEloRanking($redRanking);
        $redPlayerDefense->incEloRanking($redRanking);
        $bluePlayerAttack->incEloRanking($blueRanking);
        $bluePlayerDefense->incEloRanking($blueRanking);

    }
}