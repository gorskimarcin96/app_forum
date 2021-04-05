<?php

namespace App\Tests\MessageHandler;

use App\Message\GeneratePostJob;
use App\MessageHandler\GeneratePostJobHandler;
use App\Tests\DefaultDataTestCase;
use App\Utils\FileDataManager;
use App\Utils\Generate\PostGenerateManager;

class GeneratePostJobHandlerTest extends DefaultDataTestCase
{
    private GeneratePostJobHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $postGenerateManager = new PostGenerateManager(
            $this->documentManager,
            new FileDataManager($this->documentManager, $this->parameterBug),
            $this->parameterBug
        );
        $this->handler = new GeneratePostJobHandler($postGenerateManager);

        if ($this->userRepository->count() < 10) {
            $this->userGenerateManager->generate(10);
        }
    }

    public function testExecuteOk(): void
    {
        $countDataBefore = $this->userRepository->count();
        $message = new GeneratePostJob(10);

        $handler = $this->handler;
        $handler($message);

        $countDataAfter = $this->userRepository->count();
        $this->assertSame($countDataBefore + 10, $countDataAfter);
    }
}
