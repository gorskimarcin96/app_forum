<?php

namespace App\Repository;

use App\Document\PostFile;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @method PostFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostFile[]    findAll()
 * @method PostFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostFileRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostFile::class);
    }
}
