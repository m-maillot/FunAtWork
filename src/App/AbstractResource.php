<?php

namespace App;

use Doctrine\ORM\EntityManager;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 3/24/17
 * Time: 9:57 PM
 */
abstract class AbstractResource
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}