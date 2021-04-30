<?php

namespace App\Tests\Controller;

use App\Document\Post;

class ForumControllerTest extends MainWebTestCase
{
    public function testIndex(): void
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/');
        $response = $this->client->getResponse();

        self::assertStringContainsString('Homepage', $crawler->text());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testIndexNoLogin(): void
    {
        $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('Please sign in', $crawler->text());
        self::assertEquals(302, $response->getStatusCode());
    }


    public function testPost(): void
    {
        $this->logIn();
        $firstPost = $this->dm->getRepository(Post::class)->findOneBy([]);
        $this->client->request('GET', '/post/' . $firstPost->getId());
        $response = $this->client->getResponse();

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testPostNoLogin(): void
    {
        $firstPost = $this->dm->getRepository(Post::class)->findOneBy([]);
        $this->client->request('GET', '/post/' . $firstPost->getId());
        $response = $this->client->getResponse();
        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('Please sign in', $crawler->text());
        self::assertEquals(302, $response->getStatusCode());
    }
}
