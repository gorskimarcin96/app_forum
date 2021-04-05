<?php

namespace App\MessageHandler;

use App\Message\GenerateUserJob;
use App\Utils\Generate\UserGenerateManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GenerateUserJobHandler implements MessageHandlerInterface
{
    private UserGenerateManager $userGenerateManager;

    public function __construct(UserGenerateManager $userGenerateManager)
    {
        $this->userGenerateManager = $userGenerateManager;
    }

    public function __invoke(GenerateUserJob $message)
    {
        $this->userGenerateManager->generate($message->getLimit());
    }
}
