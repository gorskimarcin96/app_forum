<?php

namespace App\Repository;


use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\LockMode;

abstract class DefaultRepository extends ServiceDocumentRepository
{
    public function count(): int
    {
        return $this->createQueryBuilder()->count()->getQuery()->execute();
    }

    public function find($id, int $lockMode = LockMode::NONE, ?int $lockVersion = null): object
    {
        if ($id === null) {
            return $this->checkIsEmptyAndReturn($id);
        }

        if (is_array($id)) {
            [$identifierFieldName] = $this->class->getIdentifierFieldNames();

            if (isset($id[$identifierFieldName])) {
                $id = $id[$identifierFieldName];
            }
        }

        $document = $this->uow->tryGetById($id, $this->class);
        if ($document) {
            if ($lockMode !== LockMode::NONE) {
                $this->dm->lock($document, $lockMode, $lockVersion);
            }

            return $this->checkIsEmptyAndReturn($document);
        }

        $criteria = ['_id' => $id];

        if ($lockMode === LockMode::NONE) {
            return $this->checkIsEmptyAndReturn($this->getDocumentPersister()->load($criteria));
        }

        if ($lockMode === LockMode::OPTIMISTIC) {
            if (!$this->class->isVersioned) {
                throw LockException::notVersioned($this->documentName);
            }

            $document = $this->getDocumentPersister()->load($criteria);
            if ($document) {
                $this->uow->lock($document, $lockMode, $lockVersion);
            }

            return $this->checkIsEmptyAndReturn($document);
        }

        return $this->checkIsEmptyAndReturn($this->getDocumentPersister()->load($criteria, null, [], $lockMode));
    }

    private function checkIsEmptyAndReturn($document): object
    {
        if (!$document || empty($document)) {
            throw new \Exception('Object: "' . $this->getClassName() . '" is not found.');
        }

        return $document;
    }
}
