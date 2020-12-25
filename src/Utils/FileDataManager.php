<?php


namespace App\Utils;

use App\Entity\File;
use App\Entity\PostFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDataManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var string
     */
    private $rootDir;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params = null)
    {
        $this->em = $entityManager;
        $this->rootDir = $params ? ($params->get('kernel.project_dir') . File::ROOT_UPLOAD_DIR) : '';
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $type
     * @return mixed
     */
    public function upload(UploadedFile $uploadedFile, $type)
    {
        /** @var File $file */
        $file = new $type;
        $file->setName($uploadedFile->getClientOriginalName());
        $file->setExtension($uploadedFile->getClientOriginalExtension());
        $this->em->persist($file);
        $this->em->flush();

        $uploadedFile->move($this->rootDir, $file->getHash() . '.' . $uploadedFile->getClientOriginalExtension());

        return $file;
    }
}