<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:33 AM
 */

namespace App\Entity\Mapper;


use App\Entity\Group;

class GroupArrayMapper
{

    public static function transform(Group $group)
    {
        return array(
            'id' => $group->getId(),
            'name' => $group->getName(),
            'icon' => $group->getIcon()
        );
    }

    /**
     * @param $groups Group[]
     * @return array
     */
    public static function transforms($groups)
    {
        return array_map(
            function ($group) {
                return self::transform($group);
            },
            $groups
        );
    }
}