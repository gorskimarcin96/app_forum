<?php

namespace App\MessageHandler;

use App\Document\User;
use App\Message\GenerateUserJob;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class GenerateUserJobHandler implements MessageHandlerInterface
{
    /**
     * @var DocumentManager
     */
    private $dm;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $pe;

    /**
     * GenerateUser constructor.
     * @param DocumentManager $documentManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(DocumentManager $documentManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->dm = $documentManager;
        $this->pe = $passwordEncoder;
    }

    /**
     * @param GenerateUserJob $message
     * @throws MongoDBException
     */
    public function __invoke(GenerateUserJob $message)
    {
        foreach (range(0, $message->getLimit()) as $item) {
            $user[$item] = new User();
            $user[$item]->setPassword($this->pe->encodePassword($user[$item], 'password'));
            $user[$item]->setIsVerified(true);
            $this->dm->persist($user[$item]);
            $user[$item]->setEmail($user[$item]->getId() . '@test.pl');
            $this->dm->persist($user[$item]);
        }
        $this->dm->flush();
    }
}
