<?php


namespace App\Utils;


use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\PostCommentFile;
use App\Entity\PostFile;
use App\Entity\User;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ForumDataManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
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
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param FileDataManager $fileDataManager
     * @param TagRepository $tagRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        FileDataManager $fileDataManager,
        TagRepository $tagRepository
    )
    {
        $this->em = $entityManager;
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
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ExceptionInterface
     */
    public function postCreate(array $post, User $user, $tags = [], $files = []): Post
    {
        /** @var Post $post */
        $post = $this->serializer->denormalize($post, Post::class);
        $post->setUser($user);
        foreach ($tags as $tag) {
            $post->addTag($this->tagR->findOrCreate($tag));
        }
        $this->em->persist($post);

        if ($files) {
            foreach ($files as $file) {
                $postFile = $this->fileDataManager->upload($file, PostFile::class);
                $post->addPostFile($postFile);
            }
        }
        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    /**
     * @param array $postComment
     * @param User $user
     * @param array $files
     * @return PostComment
     * @throws ExceptionInterface
     */
    public function postCommentCreate(array $postComment, User $user, $files = []): PostComment
    {
        $post = $this->em->getRepository(Post::class)->find($postComment['post']);

        /** @var PostComment $postComment */
        $postComment = $this->serializer->denormalize($postComment, PostComment::class);
        $postComment->setUser($user);
        $postComment->setPost($post);

        $this->em->persist($postComment);
        if ($files) {
            foreach ($files as $file) {
                $postCommentFile = $this->fileDataManager->upload($file, PostCommentFile::class);
                $postComment->addPostCommentFile($postCommentFile);
            }
        }
        $this->em->persist($postComment);
        $this->em->flush();

        return $postComment;
    }
}