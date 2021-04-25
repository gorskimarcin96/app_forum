<?php

namespace App\Repository;

use App\Document\PostCommentFile;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @method PostCommentFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCommentFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCommentFile[]    findAll()
 * @method PostCommentFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentFileRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCommentFile::class);
    }
}
