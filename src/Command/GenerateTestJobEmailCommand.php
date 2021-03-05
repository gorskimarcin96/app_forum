<?php

namespace App\Command;

use App\Message\SendTestEmailJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class GenerateTestJobEmailCommand extends Command
{
    protected static $defaultName = 'test:send-email';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * TestMessageCommand constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (range(1, 10) as $i) {
            $this->messageBus->dispatch(new SendTestEmailJob('gorskimarcin96@gmail.com', 'temat', 'wiadomosc'));
        }

        return Command::SUCCESS;
    }
}
