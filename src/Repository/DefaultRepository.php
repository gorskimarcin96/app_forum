<?php

namespace App\Repository;


use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

abstract class DefaultRepository extends ServiceDocumentRepository
{
    public function count(): int
    {
        return $this->createQueryBuilder()->count()->getQuery()->execute();
    }
}
