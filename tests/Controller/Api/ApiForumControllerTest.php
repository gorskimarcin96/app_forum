<?php

namespace App\Tests\Controller\Api;

use App\Document\Post;
use App\Repository\PostRepository;
use App\Tests\Controller\MainWebTestCase;

class ApiForumControllerTest extends MainWebTestCase
{
    private PostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logIn();
        $this->postRepository = $this->dm->getRepository(Post::class);
    }

    public function testPostAdd(): void
    {
        $data = json_encode(['post' => ['title' => 'title', 'description' => 'description']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame('title', $jsonResponse->title);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
    }

    public function testPostAddWithNoData(): void
    {
        $this->client->request('POST', '/api/forum/post/add');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Data is empty.', $jsonResponse->error);
    }

    public function testPostAddWithTags(): void
    {
        $testTags = ['one', 'two', 'three'];
        $data = json_encode([
            'post' => ['title' => 'title', 'description' => 'description'],
            'tags' => $testTags
        ]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame('title', $jsonResponse->title);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
        foreach ($jsonResponse->arrayTag as $tag) {
            self::assertContains($tag->name, $testTags);
        }
    }

    public function testPostAddWithFiles(): void
    {
        $files = $this->prepareTestFiles(__dir__, 3);
        $data = json_encode(['post' => ['title' => 'title', 'description' => 'description']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data], ['files' => $files]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame('title', $jsonResponse->title);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
        foreach ($files as $key => $file) {
            self::assertSame($file->getClientOriginalName(), $jsonResponse->files[$key]->name);
        }
    }

    public function testPostAddWithTagsAndFiles(): void
    {
        $files = $this->prepareTestFiles(__dir__, 3);
        $testTags = ['one', 'two', 'three'];
        $data = json_encode([
            'post' => ['title' => 'title', 'description' => 'description'],
            'tags' => $testTags
        ]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data], ['files' => $files]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame('title', $jsonResponse->title);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
        foreach ($jsonResponse->arrayTag as $tag) {
            self::assertContains($tag->name, $testTags);
        }
        foreach ($files as $key => $file) {
            self::assertSame($file->getClientOriginalName(), $jsonResponse->files[$key]->name);
        }
    }

    public function testPostAddEmptyTitle(): void
    {
        $data = json_encode(['post' => ['title' => '', 'description' => 'description']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('The title cannot be empty.', $jsonResponse->error);
    }

    public function testPostAddShortTitle(): void
    {
        $data = json_encode(['post' => ['title' => 'xyz', 'description' => 'description']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Title must be at least 5 characters long.', $jsonResponse->error);
    }

    public function testPostAddEmptyDescription(): void
    {
        $data = json_encode(['post' => ['title' => 'title', 'description' => '']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('The description cannot be empty.', $jsonResponse->error);
    }

    public function testPostAddShortDescription(): void
    {
        $data = json_encode(['post' => ['title' => 'title', 'description' => 'xyz']]);
        $this->client->request('POST', '/api/forum/post/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Description must be at least 10 characters long.', $jsonResponse->error);
    }

    public function testPostGetTypes(): void
    {
        $this->client->request('GET', '/api/forum/post/get/types');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(200, $response->getStatusCode());
        self::assertSame(PostRepository::ORDER_BY_TYPES, $jsonResponse);
    }

    public function testPost(): void
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $this->client->request('GET', '/api/forum/post/id/' . $firstPost->getId());
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(200, $response->getStatusCode());
        self::assertSame($firstPost->getId(), $jsonResponse->id);
    }

    public function testPostGet(): void
    {
        $this->client->request('GET', '/api/forum/post/get/1?limit=2');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(200, $response->getStatusCode());
        self::assertCount(2, $jsonResponse);
    }

    public function testPostCommentAdd(): void
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $data = json_encode(['postComment' => ['description' => 'description', 'post' => $firstPost->getId()]]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame($firstPost->getId(), $jsonResponse->post->id);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
    }

    public function testPostCommentAddWithEmptyPost(): void
    {
        $data = json_encode(['postComment' => ['description' => 'description']]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Post id is empty.', $jsonResponse->error);
    }

    public function testPostCommentAddWithPostNotFound(): void
    {
        $data = json_encode(['postComment' => ['description' => 'description', 'post' => '-1']]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Object: "App\Document\Post" is not found.', $jsonResponse->error);
    }

    public function testPostCommentAddWithNoData(): void
    {
        $this->client->request('POST', '/api/forum/post-comment/add');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Data is empty.', $jsonResponse->error);
    }

    public function testPostCommentAddWithFiles(): void
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $files = $this->prepareTestFiles(__dir__);
        $data = json_encode(['postComment' => ['description' => 'description', 'post' => $firstPost->getId()]]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data], ['files' => $files]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(201, $response->getStatusCode());
        self::assertSame($firstPost->getId(), $jsonResponse->post->id);
        self::assertSame('description', $jsonResponse->description);
        self::assertSame($this->getUser()->getId(), $jsonResponse->user->id);
        foreach ($files as $key => $file) {
            self::assertSame($file->getClientOriginalName(), $jsonResponse->files[$key]->name);
        }
    }

    public function testPostCommentAddEmptyDescription(): void
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $data = json_encode(['postComment' => ['description' => '', 'post' => $firstPost->getId()]]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('The description cannot be empty.', $jsonResponse->error);
    }

    public function testPostCommentAddShortDescription(): void
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $data = json_encode(['postComment' => ['description' => 'xyz', 'post' => $firstPost->getId()]]);
        $this->client->request('POST', '/api/forum/post-comment/add', ['data' => $data]);
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(400, $response->getStatusCode());
        self::assertSame('Description must be at least 10 characters long.', $jsonResponse->error);
    }


    public function testPostCommentGet()
    {
        $firstPost = $this->postRepository->findOneBy([]);
        $this->client->request('GET', '/api/forum/post-comment/get/' . $firstPost->getId() . '/1?limit=2');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(200, $response->getStatusCode());
        self::assertCount(2, $jsonResponse);
    }

    public function testSidebarNav(): void
    {
        $this->client->request('GET', '/api/forum/sidebar-nav');
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent());

        self::assertEquals(200, $response->getStatusCode());
        self::assertSame(PostRepository::ORDER_BY_TYPES, $jsonResponse);
    }
}
