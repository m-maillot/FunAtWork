<?php

namespace App\Resource;

use App\AbstractResource;
use App\Entity\Group;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/24/17
 * Time: 10:45 PM
 */
class GroupResource extends AbstractResource
{
    /**
     * @return Group[]
     */
    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Group')->findBy(array(), array('name' => 'ASC'));
    }

    /**
     * @param int $groupId
     * @return Group|null|object
     */
    public function selectOne($groupId)
    {
        return $this->entityManager->getRepository('App\Entity\Group')->findOneBy(
            array('id' => $groupId)
        );
    }

    /**
     * @param Group $group
     * @return Group
     */
    public function create(Group $group)
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * Update a group from DB
     * @param Group $group
     * @return Group
     */
    public function update(Group $group)
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }
}