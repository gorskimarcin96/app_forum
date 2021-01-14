<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    /**
     * @param Post $post
     * @param int $page
     * @param int $limit
     * @return int|mixed|string
     */
    public function page(Post $post, int $page, int $limit)
    {
        return $this->createQueryBuilder('p')
            ->where('p.post = :post')
            ->setParameter('post', $post)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
