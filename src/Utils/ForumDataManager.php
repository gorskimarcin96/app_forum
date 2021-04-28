<?php

namespace App\Utils;

use App\Document\Post;
use App\Document\PostComment;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Document\User;
use App\Repository\TagRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class ForumDataManager implements InterfaceForumDataManager
{
    private DocumentManager $dm;
    private SerializerInterface $serializer;
    private FileDataManager $fileDataManager;
    private TagRepository $tagR;

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

    public function postCreate(array $post, User $user, $tags = [], $files = []): Post
    {
        /** @var Post $post */
        $post = $this->serializer->denormalize($post, Post::class);
        $post->setUser($user);
        foreach ($tags as $tag) {
            $post->addTag($this->tagR->firstOrCreate($tag));
        }

        $this->dm->persist($post);
        if ($files) {
            foreach ($files as $file) {
                $postFile = $this->fileDataManager->upload($file, PostFile::class);
                $post->addFile($postFile);
            }
        }
        $this->dm->persist($post);
        $this->dm->flush();

        return $post;
    }

    public function postCommentCreate(array $postComment, User $user, $files = []): PostComment
    {
        $this->checkPostIsEmpty($postComment);
        $post = $this->dm->getRepository(Post::class)->find($postComment['post']);

        /** @var PostComment $postComment */
        unset($postComment['post']);
        $postComment = $this->serializer->denormalize($postComment, PostComment::class);
        $postComment->setUser($user);
        $postComment->setPost($post);

        $this->dm->persist($postComment);
        if ($files) {
            foreach ($files as $file) {
                $postCommentFile = $this->fileDataManager->upload($file, PostCommentFile::class);
                $postComment->addFile($postCommentFile);
            }
        }
        $this->dm->persist($postComment);
        $this->dm->flush();

        return $postComment;
    }

    private function checkPostIsEmpty(array $postComment): void
    {
        if (!isset($postComment['post']) || !$postComment['post']) {
            throw new Exception('Post id is empty.');
        }
    }
}