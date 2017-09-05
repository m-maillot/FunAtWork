<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/26/17
 * Time: 11:33 AM
 */

namespace App\Entity\Mapper;


use App\Entity\Organization;

class OrganizationArrayMapper
{

    public static function transform(Organization $organization)
    {
        return array(
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'icon' => $organization->getIcon()
        );
    }

    /**
     * @param $organization Organization[]
     * @return array
     */
    public static function transforms($organization)
    {
        return array_map(
            function ($organization) {
                return self::transform($organization);
            },
            $organization
        );
    }
}