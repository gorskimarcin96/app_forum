<?php

namespace App\Tests\Utils;

use App\Document\Post;
use App\Document\Tag;
use App\Tests\MainKernelTestCase;
use App\Utils\FileDataManager;
use App\Utils\ForumDataManager;
use App\Utils\Generate\ImageGenerateManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ForumDataManagerTest extends MainKernelTestCase
{
    private DocumentManager $documentManager;
    private ForumDataManager $forumDataManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->documentManager = self::bootKernel()->getContainer()->get('doctrine_mongodb.odm.default_document_manager');

        $this->forumDataManager = new ForumDataManager(
            $this->documentManager,
            new Serializer([new ObjectNormalizer()], [new JsonEncoder()]),
            new FileDataManager($this->documentManager, new ParameterBag(['kernel.project_dir' => '/var/www/html'])),
            $this->documentManager->getRepository(Tag::class)
        );
    }

    public function testPostCreate(): void
    {
        $post = $this->forumDataManager->postCreate(
            ['title' => 'test title', 'description' => 'test description'],
            $this->getUser()
        );

        self::assertSame('test title', $post->getTitle());
        self::assertSame('test description', $post->getDescription());
    }

    public function testPostCreateWithTags(): void
    {
        $tags = ['test 1', 'test 2', 'test 3'];
        $post = $this->forumDataManager->postCreate(
            ['title' => 'test title', 'description' => 'test description'],
            $this->getUser(),
            $tags
        );

        self::assertSame('test title', $post->getTitle());
        self::assertSame('test description', $post->getDescription());
        foreach ($post->getTag() as $tag) {
            self::assertContains($tag->getName(), $tags);
        }
    }

    public function testPostCreateExceptionNoTitleAndNoDescription(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The title cannot be empty.');
        $this->forumDataManager->postCreate([], $this->getUser());
    }

    public function testPostCreateExceptionShortTitle(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Title must be at least 5 characters long.');
        $this->forumDataManager->postCreate(
            ['title' => '1', 'description' => 'test description'],
            $this->getUser(),
        );
    }

    public function testPostCreateWithFiles(): void
    {
        $igm = new ImageGenerateManager();
        $fileNames = ['test_1.png', 'test_2.png', 'test_3.png'];
        foreach ($fileNames as $fileName) {
            $igm->makeRandImage(20, 20, __dir__ . '/' . $fileName);
            $files[] = new UploadedFile(__dir__ . '/' . $fileName, $fileName, null, null, true);
        }
        $post = $this->forumDataManager->postCreate(
            ['title' => 'test title', 'description' => 'test description'],
            $this->getUser(),
            [],
            $files
        );

        self::assertSame('test title', $post->getTitle());
        self::assertSame('test description', $post->getDescription());
        foreach ($post->getFiles() as $file) {
            self::assertContains($file->getName(), $fileNames);
        }
    }

    public function testPostCreateWithTagsAndFiles(): void
    {
        $tags = ['test 1', 'test 2', 'test 3'];
        $igm = new ImageGenerateManager();
        $fileNames = ['test_1.png', 'test_2.png', 'test_3.png'];
        foreach ($fileNames as $fileName) {
            $igm->makeRandImage(20, 20, __dir__ . '/' . $fileName);
            $files[] = new UploadedFile(__dir__ . '/' . $fileName, $fileName, null, null, true);
        }
        $post = $this->forumDataManager->postCreate(
            ['title' => 'test title', 'description' => 'test description'],
            $this->getUser(),
            $tags
        );

        self::assertSame('test title', $post->getTitle());
        self::assertSame('test description', $post->getDescription());
        foreach ($post->getTag() as $tag) {
            self::assertContains($tag->getName(), $tags);
        }
        foreach ($post->getFiles() as $file) {
            self::assertContains($file->getName(), $fileNames);
        }
    }

    public function testPostCommentCreate(): void
    {
        $post = $this->documentManager->getRepository(Post::class)->findOneBy([]);
        $postComment = $this->forumDataManager->postCommentCreate(
            ['description' => 'test description', 'post' => $post->getId()],
            $this->getUser()
        );

        self::assertSame('test description', $postComment->getDescription());
    }

    public function testPostCommentCreateNoDescription(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The description cannot be empty.');

        $post = $this->documentManager->getRepository(Post::class)->findOneBy([]);
        $postComment = $this->forumDataManager->postCommentCreate(
            ['description' => '', 'post' => $post->getId()],
            $this->getUser()
        );

        self::assertSame('test description', $postComment->getDescription());
    }

    public function testPostCommentCreateShortDescription(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Description must be at least 10 characters long.');

        $post = $this->documentManager->getRepository(Post::class)->findOneBy([]);
        $this->forumDataManager->postCommentCreate(
            ['description' => 'test', 'post' => $post->getId()],
            $this->getUser()
        );
    }

    public function testPostCommentCreateWithEmptyPost(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Post id is empty.');

        $this->forumDataManager->postCommentCreate(
            ['description' => 'test description'],
            $this->getUser()
        );
    }

    public function testPostCommentCreateWithPostNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Object: "App\Document\Post" is not found.');

        $this->forumDataManager->postCommentCreate(
            ['description' => 'test description', 'post' => '-1'],
            $this->getUser()
        );
    }

    public function testPostCommentCreateWithImages(): void
    {
        $igm = new ImageGenerateManager();
        $fileNames = ['test_1.png', 'test_2.png', 'test_3.png'];
        foreach ($fileNames as $fileName) {
            $igm->makeRandImage(20, 20, __dir__ . '/' . $fileName);
            $files[] = new UploadedFile(__dir__ . '/' . $fileName, $fileName, null, null, true);
        }
        $post = $this->documentManager->getRepository(Post::class)->findOneBy([]);
        $postComment = $this->forumDataManager->postCommentCreate(
            ['description' => 'test description', 'post' => $post->getId()],
            $this->getUser(),
            $files
        );

        self::assertSame('test description', $postComment->getDescription());
        self::assertSame($post->getId(), $postComment->getPost()->getId());
        foreach ($postComment->getFiles() as $file) {
            self::assertContains($file->getName(), $fileNames);
        }
    }
}
