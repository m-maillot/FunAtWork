<?php

namespace App\Action\UseCase;

use App\Action\UseCase\Model\PlayerStats;
use App\Action\UseCase\Model\TeamStats;
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Babyfoot\Mapper\BabyfootGameArrayMapper;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\PlayerResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 9:15 AM
 */
class ComputeTeamStats implements UseCase
{

    /**
     * @var BabyfootGoalResource
     */
    private $goalResource;

    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * @var PlayerResource
     */
    private $playerResource;

    /**
     * AddGoal constructor.
     * @param BabyfootGoalResource $goalResource
     * @param BabyfootGameResource $gameResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootGoalResource $goalResource, BabyfootGameResource $gameResource,
                                PlayerResource $playerResource)
    {
        $this->goalResource = $goalResource;
        $this->gameResource = $gameResource;
        $this->playerResource = $playerResource;
    }

    public function execute()
    {
        $games = $this->gameResource->select(null, false);

        $teamsStats = array();
        $playersStats = array();
        foreach ($games as $game) {
            if ($game->getStatus() == BabyfootGame::GAME_OVER && $this->isValidGame($game)) {
                $redTeam = $this->initTeamStats($teamsStats, $game->getRedTeam());
                $blueTeam = $this->initTeamStats($teamsStats, $game->getRedTeam());
                $redAttack = $this->initPlayerStats($playersStats, $game, $game->getRedTeam()->getPlayerAttack());
                $redDefense = $this->initPlayerStats($playersStats, $game, $game->getRedTeam()->getPlayerDefense());
                $blueAttack = $this->initPlayerStats($playersStats, $game, $game->getBlueTeam()->getPlayerAttack());
                $blueDefense = $this->initPlayerStats($playersStats, $game, $game->getBlueTeam()->getPlayerDefense());
                $this->setStatsVictoryLoose($game, $redTeam, $blueTeam);
                $this->setStatsGamePlayed($redTeam, $blueTeam);
                $this->setStatsTeamGoalAverage($game, $redTeam, $blueTeam);
                $this->setStatsEloRanking($game, $redTeam, $blueTeam, $redAttack, $redDefense, $blueAttack, $blueDefense);
            }
        }

        return new Response(200, "", $teamsStats);
    }

    private function isValidGame(BabyfootGame $game)
    {
        return $game->getBlueTeam()->getPlayerDefense()->getId() !== $game->getBlueTeam()->getPlayerAttack()->getId()
            && $game->getRedTeam()->getPlayerDefense()->getId() !== $game->getRedTeam()->getPlayerAttack()->getId();
    }

    /**
     * @param $playersStats array
     * @param BabyfootGame $game
     * @param Player $player
     *
     * @return PlayerStats
     */
    private function initPlayerStats(&$playersStats, BabyfootGame $game, Player $player)
    {
        $playerId = $player->getId();
        if ($playersStats[$playerId]) {
            return $playersStats[$playerId];
        }
        $playerStats = new PlayerStats();
        $playerStats->player = $player;
        $playersStats[$playerId] = $playerStats;
        return $playerStats;
    }

    /**
     * @param array $teamsStats
     * @param BabyfootTeam $team
     * @return TeamStats
     */
    private function initTeamStats(&$teamsStats, BabyfootTeam $team)
    {
        $uniqueId = $this->getTeamUniqueId($team);
        if ($teamsStats[$uniqueId]) {
            return $teamsStats[$uniqueId];
        }
        $teamStats = new TeamStats();
        $teamStats->id = $uniqueId;
        $teamStats->player1 = $team->getPlayerAttack();
        $teamStats->player2 = $team->getPlayerDefense();
        $teamsStats[$uniqueId] = $teamStats;
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

    private function setStatsVictoryLoose(BabyfootGame $game, TeamStats &$redTeam, TeamStats &$blueTeam)
    {
        $redWinner = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam()) > BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());
        if ($redWinner) {
            /*
            stats . statsPlayers[game . redAttack] . victory++;
            stats . statsPlayers[game . redDefense] . victory++;
            stats . statsPlayers[game . blueAttack] . loose++;
            stats . statsPlayers[game . blueDefense] . loose++;
            */

            $redTeam->victory++;
            $blueTeam->loose++;

            /*
            stats . statsPlayers[game . redAttack] . gameSeries . push('W');
            stats . statsPlayers[game . redDefense] . gameSeries . push('W');
            stats . statsPlayers[game . blueAttack] . gameSeries . push('L');
            stats . statsPlayers[game . blueDefense] . gameSeries . push('L');
            */
        } else {
            /*
            stats . statsPlayers[game . redAttack] . loose++;
            stats . statsPlayers[game . redDefense] . loose++;
            stats . statsPlayers[game . blueAttack] . victory++;
            stats . statsPlayers[game . blueDefense] . victory++;
            */

            $redTeam->loose++;
            $blueTeam->victory++;

            /*
            stats . statsPlayers[game . redAttack] . gameSeries . push('L');
            stats . statsPlayers[game . redDefense] . gameSeries . push('L');
            stats . statsPlayers[game . blueAttack] . gameSeries . push('W');
            stats . statsPlayers[game . blueDefense] . gameSeries . push('W');
            */
        }
    }

    private function setStatsGamePlayed(TeamStats &$redTeam, TeamStats &$blueTeam)
    {
        /*
        stats . statsPlayers[game . redAttack] . gamePlayed++;
        stats . statsPlayers[game . redDefense] . gamePlayed++;
        stats . statsPlayers[game . blueAttack] . gamePlayed++;
        stats . statsPlayers[game . blueDefense] . gamePlayed++;
        */

        $redTeam->gamePlayed++;
        $blueTeam->gamePlayed++;
    }

    private function setStatsTeamGoalAverage(BabyfootGame $game, TeamStats &$redTeam, TeamStats &$blueTeam)
    {
        $redGoal = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam());
        $blueGoal = BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());

        /*
        stats.statsPlayers[game.redAttack].teamGoalaverage += game.red_score;
        stats.statsPlayers[game.redAttack].teamGoalaverage -= game.blue_score;
        stats.statsPlayers[game.redDefense].teamGoalaverage += game.red_score;
        stats.statsPlayers[game.redDefense].teamGoalaverage -= game.blue_score;

        stats.statsPlayers[game.blueAttack].teamGoalaverage += game.blue_score;
        stats.statsPlayers[game.blueAttack].teamGoalaverage -= game.red_score;
        stats.statsPlayers[game.blueDefense].teamGoalaverage += game.blue_score;
        stats.statsPlayers[game.blueDefense].teamGoalaverage -= game.red_score;
        */
        $redTeam->teamGoalAvg += $redGoal;
        $redTeam->teamGoalAvg -= $blueGoal;
        $blueTeam->teamGoalAvg -= $redGoal;
        $blueTeam->teamGoalAvg += $blueGoal;
    }

    private function setStatsEloRanking(BabyfootGame $game, TeamStats $redTeam, TeamStats $blueTeam,
                                        PlayerStats $redPlayerAttack, PlayerStats $redPlayerDefense,
                                        PlayerStats $bluePlayerAttack, PlayerStats $bluePlayerDefense)
    {
        $redWinner = BabyfootGameArrayMapper::computeGoals($game, $game->getRedTeam()) > BabyfootGameArrayMapper::computeGoals($game, $game->getBlueTeam());

        if ($redWinner) {
            $redWins = 1;
            $blueWins = 0;
        } else {
            $redWins = 0;
            $blueWins = 1;
        }

        $redTeam->eloRanking = ($redPlayerAttack->eloRanking + $redPlayerDefense->eloRanking) / 2;
        $blueTeam->eloRanking = ($bluePlayerAttack->eloRanking + $bluePlayerDefense->eloRanking) / 2;

        //Diff in ranking between blue and redteam
        $redDiff = $blueTeam->eloRanking - $redTeam->eloRanking;

        //Calculate Red and Blue Win Expectancy
        $RedWe = +(1 / (pow(10, ($redDiff / 1000)) + 1));
        $BlueWe = +(1 - $RedWe);

        $KFactor = 50;

        $redNewEloRanking = $redTeam->eloRanking + ($KFactor * ($redWins - $RedWe));
        $redRanking = +($redNewEloRanking - $redTeam->eloRanking);
        $redTeam->eloRanking = $redNewEloRanking;

        $redPlayerAttack->eloRanking += $redRanking;
        $redPlayerDefense->eloRanking += $redRanking;

        $blueNewEloRanking = $blueTeam->eloRanking + ($KFactor * ($blueWins - $BlueWe));
        $blueRanking = +($blueNewEloRanking - $blueTeam->eloRanking);
        $blueTeam->eloRanking = $blueNewEloRanking;

        $bluePlayerAttack->eloRanking += $blueRanking;
        $bluePlayerDefense->eloRanking += $blueRanking;
    }
}