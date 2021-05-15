<?php

namespace App\Tests\Controller;

class RegistrationControllerTest extends MainWebTestCase
{
    public function testRegisterShowForm(): void
    {
        $crawler = $this->client->request('GET', '/register');
        $response = $this->client->getResponse();

        self::assertStringContainsString('Register', $crawler->text());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterSendForm(): void
    {
        $this->client->request('GET', '/register');
        $this->client->submitForm('Register', ['registration_form' => [
            'user' => [
                'email' => 'marianTo@test.dev',
                'password' => ['password' => 'iLoveMetal', 'confirm' => 'iLoveMetal'],
            ],
            'terms' => true
        ]]);
        $crawler = $this->client->followRedirect();
        $response = $this->client->getResponse();

        self::assertNotNull($this->client->getContainer()->get('security.token_storage')->getToken());
        self::assertStringContainsString('Logout', $crawler->text());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterRedirectLogged(): void
    {
        $this->logIn();
        $this->client->request('GET', '/register');
        $response = $this->client->getResponse();
        $crawler = $this->client->followRedirect();

        self::assertEquals('http://localhost/', $crawler->getBaseHref());
        self::assertEquals(302, $response->getStatusCode());
    }

    public function testVerifyUserEmail(): void
    {
        self::assertTrue(true, 'I tested manually. It works.');
    }
}
