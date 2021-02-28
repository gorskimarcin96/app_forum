<?php

namespace App\Command;

use App\Document\Post;
use App\Document\User;
use App\Message\Email;
use App\Message\GeneratePostJob;
use App\Message\GenerateUserJob;
use App\MessageHandler\GeneratePostJobHandler;
use App\Repository\PostRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TestMessageCommand extends Command
{
    protected static $defaultName = 'generate-data';
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
        foreach (range(1, 1) as $i) {
            $this->messageBus->dispatch(new GenerateUserJob());
        }
        foreach (range(1, 1000) as $i) {
            $this->messageBus->dispatch(new GeneratePostJob());
        }

        return Command::SUCCESS;
    }
}
