<?php

namespace App\Tests\Utils\Generate;

use App\Utils\Generate\ImageGenerateManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImageGenerateManagerTest extends KernelTestCase
{
    private string $filePath;
    private ImageGenerateManager $igm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filePath = __dir__ . '/test_image.png';
        $this->igm = new ImageGenerateManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function testMakeRandImage50x25(): void
    {
        $this->igm->makeRandImage(50, 25, $this->filePath);

        $size = getimagesize($this->filePath);
        self::assertEquals(50, $size[0]);
        self::assertEquals(25, $size[1]);
    }

    public function testMakeRandImage500x250(): void
    {
        $this->igm->makeRandImage(500, 250, $this->filePath);

        $size = getimagesize($this->filePath);
        self::assertEquals(500, $size[0]);
        self::assertEquals(250, $size[1]);
    }

    public function testMakeRandImage2500x2500(): void
    {
        $this->igm->makeRandImage(2500, 2500, $this->filePath);

        $size = getimagesize($this->filePath);
        self::assertEquals(2500, $size[0]);
        self::assertEquals(2500, $size[1]);
    }
}
