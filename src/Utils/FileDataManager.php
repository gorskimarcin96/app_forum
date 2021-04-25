<?php

namespace App\Utils;

use App\Document\File;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDataManager implements InterfaceFileDataManager
{
    private DocumentManager $documentManager;
    private string $rootDir;

    public function __construct(DocumentManager $documentManager, ParameterBagInterface $params = null)
    {
        $this->documentManager = $documentManager;
        $this->rootDir = $params ? ($params->get('kernel.project_dir') . File::ROOT_UPLOAD_DIR) : '';
    }

    public function upload(UploadedFile $uploadedFile, $type): File
    {
        /** @var File $file */
        $file = new $type;
        $file->setName($uploadedFile->getClientOriginalName());
        $file->setExtension($uploadedFile->getClientOriginalExtension());

        $this->documentManager->persist($file);
        $this->documentManager->flush();

        $uploadedFile->move($this->rootDir, $file->getHash() . '.' . $uploadedFile->getClientOriginalExtension());

        return $file;
    }
}