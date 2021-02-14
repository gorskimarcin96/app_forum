<?php

namespace App\Document;

use MongoDB\BSON\ObjectId;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\EntityManager;

class ObjectIdGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManager $em
     * @param object|null $entity
     * @return ObjectId
     */
    public function generate(EntityManager $em, $entity): ObjectId
    {
        return new ObjectId();
    }
}