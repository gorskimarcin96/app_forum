<?php

namespace App\MessageHandler;

use App\Document\Post;
use App\Document\PostComment;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Document\Tag;
use App\Document\User;
use App\Message\GeneratePostJob;
use App\Utils\FileDataManager;
use App\Utils\ImageGenerateManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use joshtronic\LoremIpsum;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GeneratePostJobHandler implements MessageHandlerInterface
{
    /**
     * @var DocumentManager
     */
    private $dm;
    /**
     * @var LoremIpsum
     */
    private $lipsum;
    /**
     * @var object[]
     */
    private $tag;
    /**
     * @var object[]
     */
    private $post;
    /**
     * @var object[]
     */
    private $user;
    /**
     * @var int
     */
    private $countUser;
    /**
     * @var int
     */
    private $countTag;
    /**
     * @var ImageGenerateManager
     */
    private $igm;
    /**
     * @var FileDataManager
     */
    private $fileDataManager;
    /**
     * @var ContainerBagInterface
     */
    private $containerBag;

    /**
     * GenerateUser constructor.
     * @param DocumentManager $documentManager
     * @param FileDataManager $fileDataManager
     */
    public function __construct(DocumentManager $documentManager, FileDataManager $fileDataManager, ContainerBagInterface $containerBag)
    {
        $this->dm = $documentManager;
        $this->fileDataManager = $fileDataManager;
        $this->igm = new ImageGenerateManager();
        $this->lipsum = new LoremIpsum();
        $this->containerBag = $containerBag;
    }

    /**
     * @param GeneratePostJob $message
     * @throws Exception
     */
    public function __invoke(GeneratePostJob $message)
    {
        $this->user = $this->dm->getRepository(User::class)->findBy([], [], 1000);
        $this->countUser = count($this->user);

        $this->generateTags($message->getLimit() / 100);
        $this->countTag = count($this->tag);
        $this->generatePosts($message->getLimit());
        $this->generatePostComments($message->getLimit() * 10);
    }

    /**
     * @param int $limit
     * @throws MongoDBException
     */
    private function generateTags(int $limit): void
    {
        foreach (range(0, $limit) as $index => $item) {
            $this->tag[$index] = $this->createTag();
            $this->dm->persist($this->tag[$index]);
        }

        $this->dm->flush();
    }

    /**
     * @param int $limit
     * @throws Exception
     */
    private function generatePosts(int $limit): void
    {
        foreach (range(0, $limit) as $index => $item) {
            $this->post[$index] = $this->createPost();
            $this->dm->persist($this->post[$index]);

            foreach (range(0, random_int(0, 5)) as $i) {
                $this->post[$index] = $this->addFile($this->post[$index], PostFile::class);
            }
        }

        $this->dm->flush();
    }

    /**
     * @param int $limit
     * @throws MongoDBException
     * @throws Exception
     */
    private function generatePostComments(int $limit): void
    {
        foreach (range(0, $limit) as $index => $item) {
            $postComment[$index] = $this->createPostComment();
            $this->dm->persist($postComment[$index]);

            foreach (range(0, random_int(0, 5)) as $i) {
                $postComment[$index] = $this->addFile($postComment[$index], PostCommentFile::class);
            }
        }

        $this->dm->flush();
    }

    /**
     * @param $entity
     * @param $type
     * @param int $width
     * @param int $height
     * @return Post|PostComment
     * @throws MongoDBException
     * @throws Exception
     */
    private function addFile($entity, $type, $width = 800, $height = 600)
    {
        if (!in_array($type, [PostFile::class, PostCommentFile::class], true)) {
            throw new RuntimeException('Type is not valid.');
        }

        $path = $this->containerBag->get('kernel.project_dir') . '/public/test_img.png';
        $this->igm->makeRandImage($width, $height, $path);

        $uploadedFile = new UploadedFile($path, 'test_img.png', null, null, true);
        $postFile = $this->fileDataManager->upload($uploadedFile, $type);
        $entity->addFile($postFile);

        return $entity;
    }

    /**
     * @return Post
     * @throws Exception
     */
    private function createPost(): Post
    {
        $post = new Post();
        $post->setTitle($this->lipsum->words(random_int(3, 20)));
        $post->setDescription($this->lipsum->paragraphs(random_int(1, 7), 'p'));
        $post->setUser($this->user[random_int(0, $this->countUser - 1)]);
        foreach (range(0, 7) as $tag) {
            $post->addTag($this->tag[$this->countTag - 1]);
        }

        return $post;
    }

    /**
     * @return PostComment
     * @throws Exception
     */
    private function createPostComment(): PostComment
    {
        $countPost = count($this->post);
        $postComment = new PostComment();
        $postComment->setDescription($this->lipsum->paragraphs(random_int(1, 7), 'p'));
        $postComment->setUser($this->user[random_int(0, $this->countUser - 1)]);
        $postComment->setPost($this->post[random_int(0, $countPost - 1)]);

        return $postComment;
    }

    /**
     * @return Tag
     */
    private function createTag(): Tag
    {
        $tag = new Tag();
        $this->dm->persist($tag);
        $tag->setName($tag->getId());
        $this->dm->persist($tag);

        return $tag;
    }
}
