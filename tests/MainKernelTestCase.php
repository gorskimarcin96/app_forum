<?php

namespace App\Tests;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class MainKernelTestCase extends KernelTestCase
{
    public function getUser(): User
    {
        $documentManager = self::bootKernel()->getContainer()->get('doctrine_mongodb.odm.default_document_manager');
        $userRepository = $documentManager->getRepository(User::class);
        if(!$userRepository->count()){
            $user = new User();
            $user->setEmail('test@test.pl');
            $documentManager->persist($user);
            $documentManager->flush();
        }

        return $userRepository->findOneBy([]);
    }
}
