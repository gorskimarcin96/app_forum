<?php

namespace App\Tests\MessageHandler;

use App\Message\GenerateUserJob;
use App\MessageHandler\GenerateUserJobHandler;
use App\Tests\DefaultDataTestCase;

class GenerateUserJobHandlerTest extends DefaultDataTestCase
{
    private GenerateUserJobHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new GenerateUserJobHandler($this->userGenerateManager);
    }

    public function testExecuteOk(): void
    {
        $countDataBefore = $this->userRepository->count();
        $message = new GenerateUserJob(10);

        $handler = $this->handler;
        $handler($message);

        $countDataAfter = $this->userRepository->count();
        $this->assertSame($countDataBefore + 10, $countDataAfter);
    }
}
