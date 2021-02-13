<?php

namespace App\Utils;

use App\Document\Post;
use App\Document\PostComment;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Document\User;
use App\Repository\TagRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ForumDataManager
{
    /**
     * @var DocumentManager
     */
    private $dm;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var FileDataManager
     */
    private $fileDataManager;
    /**
     * @var TagRepository
     */
    private $tagR;

    /**
     * ForumDataManager constructor.
     * @param DocumentManager $documentManager
     * @param SerializerInterface $serializer
     * @param FileDataManager $fileDataManager
     * @param TagRepository $tagRepository
     */
    public function __construct(
        DocumentManager $documentManager,
        SerializerInterface $serializer,
        FileDataManager $fileDataManager,
        TagRepository $tagRepository
    )
    {
        $this->dm = $documentManager;
        $this->serializer = $serializer;
        $this->fileDataManager = $fileDataManager;
        $this->tagR = $tagRepository;
    }

    /**
     * @param array $post
     * @param User $user
     * @param array $tags
     * @param array $files
     * @return Post
     * @throws ExceptionInterface
     * @throws MongoDBException
     */
    public function postCreate(array $post, User $user, $tags = [], $files = []): Post
    {
        /** @var Post $post */
        $post = $this->serializer->denormalize($post, Post::class);
        $post->setUser($user);
        foreach ($tags as $tag) {
            $post->addTag($this->tagR->findOrCreate($tag));
        }

        $this->dm->persist($post);
        if($files) {
            foreach ($files as $file) {
                $postFile = $this->fileDataManager->upload($file, PostFile::class);
                $post->addPostFile($postFile);
            }
        }
        $this->dm->persist($post);
        $this->dm->flush();

        return $post;
    }

    /**
     * @param array $postComment
     * @param User $user
     * @param array $files
     * @return PostComment
     * @throws ExceptionInterface
     * @throws MongoDBException
     */
    public function postCommentCreate(array $postComment, User $user, $files = []): PostComment
    {
        $post = $this->dm->getRepository(Post::class)->find($postComment['post']);

        /** @var PostComment $postComment */
        $postComment = $this->serializer->denormalize($postComment, PostComment::class);
        $postComment->setUser($user);
        $postComment->setPost($post);

        $this->dm->persist($postComment);
        if ($files) {
            foreach ($files as $file) {
                $postCommentFile = $this->fileDataManager->upload($file, PostCommentFile::class);
                $postComment->addPostCommentFile($postCommentFile);
            }
        }
        $this->dm->persist($postComment);
        $this->dm->flush();

        return $postComment;
    }
}