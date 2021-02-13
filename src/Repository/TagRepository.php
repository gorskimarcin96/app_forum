<?php

namespace App\Repository;

use App\Document\Tag;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceDocumentRepository
{
    /**
     * TagRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param string $name
     * @return Tag|array|Iterator|int|DeleteResult|InsertOneResult|UpdateResult|object
     * @throws MongoDBException
     */
    public function findOrCreate(string $name)
    {
        $tag = $this->createQueryBuilder()
            ->field('name')->equals($name)
            ->getQuery()
            ->execute();

        if (!$tag->current()) {
            $tag = new Tag();
            $tag->setName($name);
            $this->getDocumentManager()->persist($tag);
            $this->getDocumentManager()->flush();

            return $tag;
        }

        return $tag->current();
    }
}
