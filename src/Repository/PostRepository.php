<?php

namespace App\Repository;

use App\Document\Post;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class PostRepository extends ServiceDocumentRepository
{
    public const ORDER_BY_TYPES = [
        'latest', 'popular', //'solved', 'unsolved', 'no replies yet'
    ];

    /**
     * PostRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $type
     * @return int|mixed|string
     */
    public function page(int $page, int $limit = 10, string $type = 'latest')
    {
        $query = $this->createQueryBuilder();

        switch ($type) {
            case self::ORDER_BY_TYPES[1]:
                $query->sort('numberEntries', 'DESC');
                break;
            case self::ORDER_BY_TYPES[0]:
            default:
                $query->sort('createdAt', 'DESC');
                break;
        }

        return $query
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->getQuery()
            ->toArray();
    }
}
