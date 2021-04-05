<?php

namespace App\MessageHandler;

use App\Message\GeneratePostJob;
use App\Utils\Generate\PostGenerateManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GeneratePostJobHandler implements MessageHandlerInterface
{
    private PostGenerateManager $postGenerateManager;

    public function __construct(PostGenerateManager $postGenerateManager)
    {
        $this->postGenerateManager = $postGenerateManager;
    }

    public function __invoke(GeneratePostJob $message)
    {
        $this->postGenerateManager->generate($message->getLimit());
    }
}
