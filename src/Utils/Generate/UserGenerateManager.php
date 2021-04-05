<?php


namespace App\Utils\Generate;


use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserGenerateManager
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
        foreach (range(1, $quantity) as $item) {
            $user[$item] = new User();
            $user[$item]->setPassword($this->passwordEncoder->encodePassword($user[$item], 'password'));
            $user[$item]->setIsVerified(true);
            $this->documentManager->persist($user[$item]);
            $user[$item]->setEmail($user[$item]->getId() . '@test.pl');
            $this->documentManager->persist($user[$item]);
        }
        $this->documentManager->flush();
    }
}