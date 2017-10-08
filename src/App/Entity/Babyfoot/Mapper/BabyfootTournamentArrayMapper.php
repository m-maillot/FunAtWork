<?php

namespace App\Entity\Babyfoot\Mapper;


use App\Entity\Babyfoot\BabyfootGameKnockout;
use App\Entity\Babyfoot\BabyfootTournament;
use Doctrine\ORM\PersistentCollection;

class BabyfootTournamentArrayMapper
{

    public static function transform(BabyfootTournament $tournament)
    {
        return array(
            'id' => $tournament->getId(),
            'name' => $tournament->getName(),
            'startDate' => $tournament->getStartedDate()->format(\DateTime::ISO8601),
            'rounds' => self::splitByRound($tournament->getGames())
        );

    }

    /**
     * @param $knockoutGames PersistentCollection|BabyfootGameKnockout[]
     * @return array
     */
    public static function splitByRound($knockoutGames)
    {
        $roundArray = array();

        /**
         * @var $knockoutArray BabyfootGameKnockout[]
         */
        $knockoutArray = $knockoutGames->toArray();
        foreach ($knockoutArray as $knockout) {
            if (!$roundArray[$knockout->getRound()]) {
                $roundArray[$knockout->getRound()] = array('index' => $knockout->getRound(), 'games' => array());
            }
            array_push($roundArray[$knockout->getRound()]['games'], self::transformKnockout($knockout));
        }
        $roundFinalArray = array();
        foreach ($roundArray as $round) {
            array_push($roundFinalArray, $round);
        }
        return $roundFinalArray;
    }

    /**
     * @param $knockoutGames PersistentCollection|BabyfootGameKnockout[]
     * @return array
     */
    public static function transformKnockouts($knockoutGames)
    {
        $knockoutArray = $knockoutGames->toArray();
        return array_map(
            function ($knockoutGame) {
                return self::transformKnockout($knockoutGame);
            },
            $knockoutArray
        );
    }

    /**
     * @param BabyfootGameKnockout $knockoutGame
     * @return array
     */
    public static function transformKnockout(BabyfootGameKnockout $knockoutGame)
    {
        $game = BabyfootGameArrayMapper::transform($knockoutGame->getGame());

        if ($knockoutGame->getRedWinnerOf()) {
            $game['redWinnerOf'] = $knockoutGame->getRedWinnerOf()->getGame()->getId();
            $game['blueWinnerOf'] = $knockoutGame->getBlueWinnerOf()->getGame()->getId();
        }

        return $game;
    }


}