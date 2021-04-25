<?php

namespace App\Tests\Utils\Generate;

use App\Document\User;
use App\Repository\UserRepository;
use App\Utils\Generate\UserGenerateManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class GenerateKernelTestCase extends KernelTestCase
{
    /**
     * @var DocumentManager|object|null
     */
    protected $documentManager;
    protected ParameterBag $parameterBug;
    protected MessageBusInterface $messageBus;
    protected UserGenerateManager $userGenerateManager;
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->documentManager = self::bootKernel()->getContainer()->get('doctrine_mongodb.odm.default_document_manager');
        $this->parameterBug = new ParameterBag(['kernel.project_dir' => '/var/www/html']);

        $userPasswordEncoder = self::bootKernel()->getContainer()->get('security.password_encoder');
        $this->userGenerateManager = new UserGenerateManager($this->documentManager, $userPasswordEncoder);
        $this->userRepository = $this->documentManager->getRepository(User::class);
    }
}
