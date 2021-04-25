<?php

namespace App\Repository;

use App\Document\Post;
use App\Document\PostComment;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;
use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

/**
 * @method PostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentRepository extends DefaultRepository
{
    /**
     * PostCommentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    /**
     * @param Post $post
     * @param int $page
     * @param int $limit
     * @return array|Iterator|int|DeleteResult|InsertOneResult|UpdateResult|object|null
     * @throws MongoDBException
     */
    public function page(Post $post, int $page, int $limit)
    {
        return $this->createQueryBuilder()
            ->field('post')->references($post)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->getQuery()
            ->execute();
    }
}
