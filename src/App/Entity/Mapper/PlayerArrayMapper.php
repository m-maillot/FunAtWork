<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:33 AM
 */

namespace App\Entity\Mapper;


use App\Entity\Player;

class PlayerArrayMapper
{

    public static function transform(Player $player)
    {
        return array(
            'id' => $player->getId(),
            'name' => $player->getName(),
            'avatar' => $player->getAvatar()
        );
    }

    /**
     * @param $players Player[]
     * @return array
     */
    public static function transforms($players)
    {
        return array_map(
            function ($player) {
                return self::transform($player);
            },
            $players
        );
    }
}