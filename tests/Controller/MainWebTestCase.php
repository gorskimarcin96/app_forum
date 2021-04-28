<?php


namespace App\Tests\Controller;


use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MainWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected DocumentManager $dm;
    private string $firewall = 'main';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->dm = $this->client->getContainer()->get('doctrine_mongodb.odm.document_manager');
    }

    protected function logIn(User $user = null): void
    {
        if (!$user) {
            $user = $this->getUser();
        }
        $token = new UsernamePasswordToken($user, null, $this->firewall, $user->getRoles());

        $this->setTokenInStorage($token);
        $this->setTokenInCookie($token);

    }

    protected function getUser($email = 'test@test'): User
    {
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);

        return $user ?? $this->createUser($email);
    }

    private function createUser(string $email): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword('test');
        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }

    private function setTokenInStorage(UsernamePasswordToken $token): void
    {
        $this->client->getContainer()->get('security.token_storage')->setToken($token);
    }

    private function setTokenInCookie(UsernamePasswordToken $token): void
    {
        $session = $this->client->getContainer()->get('session');
        $session->set('_security_' . $this->firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function prepareTestFiles(string $path = __dir__, int $quantity = 1): array
    {
        foreach (range(1, $quantity) as $number) {
            $nameFile = 'test_image' . $number . '.png';
            $pathCreateFileTo = $path . '/' . $nameFile;
            copy(__dir__ . '/../test_image.png', $pathCreateFileTo);
            $files[] = new UploadedFile($pathCreateFileTo, $nameFile, 'image/png');
        }

        return $files ?? [];
    }
}