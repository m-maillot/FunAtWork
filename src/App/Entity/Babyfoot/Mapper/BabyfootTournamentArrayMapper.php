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
            'started' => $tournament->getStartedDate()->getTimestamp(),
            'games' => self::transformKnockouts($tournament->getGames())
        );

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
        if ($knockoutGame->getRedWinnerOf()) {
            return array(
                'id' => $knockoutGame->getGame()->getId(),
                'round' => $knockoutGame->getRound(),
                'redWinnerOf' => $knockoutGame->getRedWinnerOf()->getGame()->getId(),
                'blueWinnerOf' => $knockoutGame->getBlueWinnerOf()->getGame()->getId(),
                'detail' => BabyfootGameArrayMapper::transform($knockoutGame->getGame())
            );
        } else {
            return array(
                'id' => $knockoutGame->getGame()->getId(),
                'round' => $knockoutGame->getRound(),
                'detail' => BabyfootGameArrayMapper::transform($knockoutGame->getGame())
            );
        }
    }


}