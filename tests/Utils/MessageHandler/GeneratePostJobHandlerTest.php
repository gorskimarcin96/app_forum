<?php

namespace App\Tests\Utils\MessageHandler;

use App\Message\GeneratePostJob;
use App\MessageHandler\GeneratePostJobHandler;
use App\Utils\Generate\PostGenerateManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GeneratePostJobHandlerTest extends KernelTestCase
{
    private GeneratePostJobHandler $handler;

    public function testExecuteOk(): void
    {
        $message = new GeneratePostJob(2);

        $handler = $this->handler;
        $handler($message);

        self::assertTrue(true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $postGenerateManager = $this->getMockBuilder(PostGenerateManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler = new GeneratePostJobHandler($postGenerateManager);
    }
}
