<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 6/26/17
 * Time: 9:07 AM
 */

namespace App\Action\UseCase;


use App\Action\UseCase\Model\PlayerStats;

class EloRanking
{

    /**
     * Calculate scores for each player.
     * Players should have position:
     *   Index 0: Red player 1
     *   Index 1: Red player 2
     *   Index 2: Blue player 1
     *   Index 3: Blue player 2
     * TODO: Find a better way to parse and return players.
     * @param $players array of red and blue players, should only contain a total of 4 players.
     * @param $redWin true if red team has won.
     *
     * @return array
     */
    public static function calculateGameScore($players, $redWin)
    {
        // Player fields.
        $redPlayer1 = $players[0];
        $redPlayer2 = $players[1];
        $bluePlayer1 = $players[2];
        $bluePlayer2 = $players[3];

        return EloRanking::calculateGameScoreInternal($redPlayer1, $redPlayer2, $bluePlayer1, $bluePlayer2, $redWin);
    }

    /**
     * Calculate scores for each player.
     * @param $redPlayer1 PlayerStats 1 on red team.
     * @param $redPlayer2 PlayerStats 2 on red team.
     * @param $bluePlayer1 PlayerStats 1 on blue team.
     * @param $bluePlayer2 PlayerStats 2 on blue team.
     * @param $redWin true if red team has won.
     * @return array
     */
    private static function calculateGameScoreInternal(PlayerStats $redPlayer1, PlayerStats $redPlayer2,
                                                       PlayerStats $bluePlayer1, PlayerStats $bluePlayer2,
                                                       $redWin)
    {

        /**
         * Average player score for each team.
         * We use the average team score to calculate rating for each team.
         */
        $averageRedScore = ($redPlayer1->eloRanking + $redPlayer2->eloRanking) / 2;
        $averageBlueScore = ($bluePlayer1->eloRanking + $bluePlayer2->eloRanking) / 2;
        // Log . d(TAG, String . format("Average scores; red: %4d, blue: %4d", averageRedScore, averageBlueScore));

        /**
         * Team performance rating.
         * The rating is the same regardless of who wins the match.
         */
        $redRating = EloRanking::calculatePerformanceRating($averageRedScore, $averageBlueScore);
        $blueRating = EloRanking::calculatePerformanceRating($averageBlueScore, $averageRedScore);
        // Log . d(TAG, String . format("Team performance rating: red: %.3f, blue: %.3f", redRating, blueRating));

        if ($redWin) {
            /**
             * Red players performance rating on red victory.
             */
            if ($redPlayer1->eloRanking >= $redPlayer2->eloRanking) {
                $redRating1 = EloRanking::calculatePerformanceRating($redPlayer1->eloRanking, $redPlayer2->eloRanking);
                $redRating2 = EloRanking::calculatePerformanceRating($redPlayer2->eloRanking, $redPlayer1->eloRanking);
            } else {
                $redRating1 = EloRanking::calculatePerformanceRating($redPlayer2->eloRanking, $redPlayer1->eloRanking);
                $redRating2 = EloRanking::calculatePerformanceRating($redPlayer1->eloRanking, $redPlayer2->eloRanking);
            }
            if ($bluePlayer1->eloRanking >= $bluePlayer2->eloRanking) {
                $blueRating1 = EloRanking::calculatePerformanceRating($bluePlayer2->eloRanking, $bluePlayer1->eloRanking);
                $blueRating2 = EloRanking::calculatePerformanceRating($bluePlayer1->eloRanking, $bluePlayer2->eloRanking);
            } else {
                $blueRating1 = EloRanking::calculatePerformanceRating($bluePlayer1->eloRanking, $bluePlayer2->eloRanking);
                $blueRating2 = EloRanking::calculatePerformanceRating($bluePlayer2->eloRanking, $bluePlayer1->eloRanking);
            }

            /**
             * Team score for red victory.
             */
            $redScore = 24 * (1 - $redRating);
            $blueScore = 24 * (0 - $blueRating);
        } else {
            /**
             * Players performance rating on blue victory.
             */
            if ($redPlayer1->eloRanking >= $redPlayer2->eloRanking) {
                $redRating1 = EloRanking::calculatePerformanceRating($redPlayer2->eloRanking, $redPlayer1->eloRanking);
                $redRating2 = EloRanking::calculatePerformanceRating($redPlayer1->eloRanking, $redPlayer2->eloRanking);
            } else {
                $redRating1 = EloRanking::calculatePerformanceRating($redPlayer1->eloRanking, $redPlayer2->eloRanking);
                $redRating2 = EloRanking::calculatePerformanceRating($redPlayer2->eloRanking, $redPlayer1->eloRanking);
            }
            if ($bluePlayer1->eloRanking >= $bluePlayer2->eloRanking) {
                $blueRating1 = EloRanking::calculatePerformanceRating($bluePlayer1->eloRanking, $bluePlayer2->eloRanking);
                $blueRating2 = EloRanking::calculatePerformanceRating($bluePlayer2->eloRanking, $bluePlayer1->eloRanking);
            } else {
                $blueRating1 = EloRanking::calculatePerformanceRating($bluePlayer2->eloRanking, $bluePlayer1->eloRanking);
                $blueRating2 = EloRanking::calculatePerformanceRating($bluePlayer1->eloRanking, $bluePlayer2->eloRanking);
            }

            /**
             * Team score for blue victory.
             */
            $redScore = 24 * (0 - $redRating);
            $blueScore = 24 * (1 - $blueRating);
        }

        // Log . d(TAG, String . format("Red team performance rating: 1: %.3f, 2: %.3f", redRating1, redRating2));
        // Log . d(TAG, String . format("Blue team performance rating: 1: %.3f, 2: %.3f", blueRating1, blueRating2));
        // Log . d(TAG, String . format("Team scores: red: %.3f, blue: %.3f", redScore, blueScore));

        /**
         * Player score.
         */
        $redScore1 = $redScore * $redRating1;
        $redScore2 = $redScore * $redRating2;
        $blueScore1 = $blueScore * $blueRating1;
        $blueScore2 = $blueScore * $blueRating2;

        // Log . d(TAG, String . format("Red team scores: 1: %2d, 2: %2d", redScore1, redScore2));
        // Log . d(TAG, String . format("Blue team scores: 1: %2d, 2: %2d", blueScore1, blueScore2));

        /**
         * Add score alterations.
         */
        $redPlayer1->eloRanking = $redScore1;
        $redPlayer2->eloRanking = $redScore2;
        $bluePlayer1->eloRanking = $blueScore1;
        $bluePlayer2->eloRanking = $blueScore2;

        return array($redPlayer1, $redPlayer2, $bluePlayer1, $bluePlayer2);
    }

    /**
     * Calculate performance rating using the "algorithm of 400".
     * The hypothetical rating can be used as an indicator for the likelihood of winning, a rating
     * of 0.60 for one team will give the other team a rating of 0.40.
     * Performance rating can only be calculated using a set of two scores, but can be used on
     * both team (average) scores and player scores.
     * {@see https://en.wikipedia.org/wiki/Elo_rating_system}
     *
     * @param $score1 int Score for team/player 1.
     * @param $score2 int Score for team/player 2.
     * @return int Performance rating for team/player 1.
     */
    private static function calculatePerformanceRating($score1, $score2)
    {
        return 1 / (1 + pow(10, ($score2 - $score1) / 400));
    }
}