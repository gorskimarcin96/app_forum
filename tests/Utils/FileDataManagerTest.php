<?php

namespace App\Tests\Utils;

use App\Document\File;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Utils\FileDataManager;
use App\Utils\Generate\ImageGenerateManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDataManagerTest extends KernelTestCase
{
    private string $filePath;
    private FileDataManager $fileDataManager;

    protected function setUp(): void
    {
        parent::setUp();
        $documentManager = self::bootKernel()->getContainer()->get('doctrine_mongodb.odm.default_document_manager');

        $this->filePath = __dir__ . '/test_image.png';
        $this->fileDataManager = new FileDataManager(
            $documentManager,
            new ParameterBag(['kernel.project_dir' => '/var/www/html'])
        );

        $imageGenerateManager = new ImageGenerateManager();
        $imageGenerateManager->makeRandImage(200, 200, $this->filePath);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testUploadPostFile(): void
    {
        $uploadedFile = new UploadedFile($this->filePath, 'test_img.png', null, null, true);
        $obj = $this->fileDataManager->upload($uploadedFile, new PostFile());

        self::assertSame(PostFile::class, get_class($obj));
        self::assertSame('test_img.png', $obj->getName());
        self::assertSame('png', $obj->getExtension());
        self::assertFileExists(__dir__ . '/../..' . File::ROOT_UPLOAD_DIR . '/' . $obj->getHash() . '.' . $obj->getExtension());
    }

    public function testUploadPostCommentFile(): void
    {
        $uploadedFile = new UploadedFile($this->filePath, 'test_img.png', null, null, true);
        $obj = $this->fileDataManager->upload($uploadedFile, new PostCommentFile());

        self::assertSame(PostCommentFile::class, get_class($obj));
        self::assertSame('test_img.png', $obj->getName());
        self::assertSame('png', $obj->getExtension());
        self::assertFileExists(__dir__ . '/../..' . File::ROOT_UPLOAD_DIR . '/' . $obj->getHash() . '.' . $obj->getExtension());
    }
}
