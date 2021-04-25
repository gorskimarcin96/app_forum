<?php


namespace App\Utils;


use App\Document\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface InterfaceFileDataManager
{
    public function upload(UploadedFile $uploadedFile, $type): File;
}