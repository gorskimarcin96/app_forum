<?php


namespace App\Utils\Generate;


use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserGenerateManager implements GenerateManager
{
    private DocumentManager $documentManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(DocumentManager $documentManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->documentManager = $documentManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function generate(int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        for ($i = 0; $i < $quantity; $i++) {
            $user[$i] = new User();
            $user[$i]->setPassword($this->passwordEncoder->encodePassword($user[$i], 'password'));
            $user[$i]->setIsVerified(true);
            $this->documentManager->persist($user[$i]);
            $user[$i]->setEmail($user[$i]->getId() . '@test.pl');
            $this->documentManager->persist($user[$i]);
        }
        $this->documentManager->flush();
    }
}