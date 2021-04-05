<?php

namespace App\Command;

use App\Message\GeneratePostJob;
use App\Message\GenerateUserJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GenerateDataMessageCommand extends Command
{
    protected static $defaultName = 'generate-data';
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (range(1, 10) as $i) {
            $this->messageBus->dispatch(new GenerateUserJob(100));
        }
        foreach (range(1, 10000) as $i) {
            $this->messageBus->dispatch(new GeneratePostJob(100));
        }

        return Command::SUCCESS;
    }
}
