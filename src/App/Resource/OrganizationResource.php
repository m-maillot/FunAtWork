<?php

namespace App\Resource;

use App\AbstractResource;
use App\Entity\Organization;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/24/17
 * Time: 10:45 PM
 */
class OrganizationResource extends AbstractResource
{
    /**
     * @return Organization[]
     */
    public function select()
    {
        return $this->entityManager->getRepository('App\Entity\Organization')->findBy(array(), array('name' => 'ASC'));
    }

    /**
     * @param int $organizationId
     * @return Organization|null|object
     */
    public function selectOne($organizationId)
    {
        return $this->entityManager->getRepository('App\Entity\Organization')->findOneBy(
            array('id' => $organizationId)
        );
    }

    /**
     * @param Organization $organization
     * @return Organization
     */
    public function create(Organization $organization)
    {
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
        return $organization;
    }

    /**
     * Update a group from DB
     * @param Organization $organization
     * @return Organization
     */
    public function update(Organization $organization)
    {
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
        return $organization;
    }
}