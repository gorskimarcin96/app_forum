<?php

namespace App\Tests\Utils\MessageHandler;

use App\Message\GenerateUserJob;
use App\MessageHandler\GenerateUserJobHandler;
use App\Utils\Generate\UserGenerateManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateUserJobHandlerTest extends KernelTestCase
{
    private GenerateUserJobHandler $handler;

    public function testExecuteOk(): void
    {
        $message = new GenerateUserJob(2);

        $handler = $this->handler;
        $handler($message);

        self::assertTrue(true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $userGenerateManager = $this->getMockBuilder(UserGenerateManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler = new GenerateUserJobHandler($userGenerateManager);
    }
}
