<?php

namespace App\Tests\Utils\Generate;

use App\Document\Post;
use App\Document\PostComment;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Repository\PostCommentFileRepository;
use App\Repository\PostCommentRepository;
use App\Repository\PostFileRepository;
use App\Repository\PostRepository;
use App\Utils\FileDataManager;
use App\Utils\Generate\PostGenerateManager;

class PostGenerateManagerTest extends GenerateKernelTestCase
{
    private PostRepository $postRepository;
    private PostGenerateManager $postGenerator;
    private PostCommentRepository $postCommentRepository;
    private PostFileRepository $postPostFileRepository;
    private PostCommentFileRepository $postPostCommentFileRepository;

    public function testGenerateQuantity2(): void
    {
        $testQuantity = 2;

        $countPostDataBefore = $this->postRepository->count();
        $countPostFileDataBefore = $this->postPostFileRepository->count();
        $countPostCommentDataBefore = $this->postCommentRepository->count();
        $countPostCommentFileDataBefore = $this->postPostCommentFileRepository->count();

        $this->postGenerator->generate($testQuantity);

        $countPostDataAfter = $this->postRepository->count();
        $countPostFileDataAfter = $this->postPostFileRepository->count();
        $countPostCommentDataAfter = $this->postCommentRepository->count();
        $countPostCommentFileDataAfter = $this->postPostCommentFileRepository->count();

        self::assertSame($countPostDataBefore + $testQuantity, $countPostDataAfter);
        self::assertSame(
            $countPostCommentDataBefore + ($testQuantity * PostGenerateManager::POST_COMMENT_MULTIPLICATION),
            $countPostCommentDataAfter
        );
        self::assertGreaterThanOrEqual(
            $countPostFileDataBefore + ($testQuantity * 0),
            $countPostFileDataAfter
        );
        self::assertLessThanOrEqual(
            $countPostFileDataBefore + ($testQuantity * PostGenerateManager::RAND_FILE_NUMBER),
            $countPostFileDataAfter
        );
        self::assertGreaterThanOrEqual(
            $countPostCommentFileDataBefore + ($testQuantity * 0),
            $countPostCommentFileDataAfter
        );
        self::assertLessThanOrEqual(
            $countPostCommentFileDataBefore +
            ($testQuantity * PostGenerateManager::POST_COMMENT_MULTIPLICATION * PostGenerateManager::RAND_FILE_NUMBER),
            $countPostCommentFileDataAfter
        );
    }

    public function testGenerateQuantity0(): void
    {
        $countDataBefore = $this->postRepository->count();
        $this->postGenerator->generate(0);
        $countDataAfter = $this->postRepository->count();

        self::assertSame($countDataBefore + 0, $countDataAfter);
    }

    public function testGenerateQuantityMinus2(): void
    {
        $countDataBefore = $this->postRepository->count();
        $this->postGenerator->generate(-2);
        $countDataAfter = $this->postRepository->count();

        self::assertSame($countDataBefore + 0, $countDataAfter);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->postGenerator = new PostGenerateManager(
            $this->documentManager,
            new FileDataManager($this->documentManager, $this->parameterBug),
            $this->parameterBug
        );
        $this->postRepository = $this->documentManager->getRepository(Post::class);
        $this->postCommentRepository = $this->documentManager->getRepository(PostComment::class);
        $this->postPostFileRepository = $this->documentManager->getRepository(PostFile::class);
        $this->postPostCommentFileRepository = $this->documentManager->getRepository(PostCommentFile::class);
        if ($this->userRepository->count() < 10) {
            $this->userGenerateManager->generate(10);
        }
    }
}
