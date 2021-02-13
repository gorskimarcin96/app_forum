<?php

namespace App\Utils;

use App\Document\File;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDataManager
{
    /**
     * @var DocumentManager
     */
    private $dm;
    /**
     * @var string
     */
    private $rootDir;

    /**
     * FileDataManager constructor.
     * @param DocumentManager $documentManager
     * @param ParameterBagInterface|null $params
     */
    public function __construct(DocumentManager $documentManager, ParameterBagInterface $params = null)
    {
        $this->dm = $documentManager;
        $this->rootDir = $params ? ($params->get('kernel.project_dir') . File::ROOT_UPLOAD_DIR) : '';
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $type
     * @return File
     * @throws MongoDBException
     */
    public function upload(UploadedFile $uploadedFile, $type): File
    {
        /** @var File $file */
        $file = new $type;
        $file->setName($uploadedFile->getClientOriginalName());
        $file->setExtension($uploadedFile->getClientOriginalExtension());

        $this->dm->persist($file);
        $this->dm->flush();

        $uploadedFile->move($this->rootDir, $file->getHash() . '.' . $uploadedFile->getClientOriginalExtension());

        return $file;
    }
}