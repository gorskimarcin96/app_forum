<?php

namespace App\Repository;

use App\Document\Post;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class PostRepository extends DefaultRepository
{
    public const ORDER_BY_TYPES = [
        'latest', 'popular', //'solved', 'unsolved', 'no replies yet'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function page(int $page, int $limit = 10, string $type = 'latest', string $phrase = null): array
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

        if ($phrase) {
            foreach (['title', 'description'] as $column) {
                $query->addOr($query->expr()->field($column)->equals(['$regex' => $phrase]));
            }
        }

        return $query
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->getQuery()
            ->toArray();
    }
}
