<?php


namespace App\Utils\Generate;


use App\Document\Post;
use App\Document\PostComment;
use App\Document\PostCommentFile;
use App\Document\PostFile;
use App\Document\Tag;
use App\Document\User;
use App\Utils\FileDataManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use joshtronic\LoremIpsum;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostGenerateManager implements GenerateManager
{
    public const USER_NUMBER = 1000;
    public const TAG_NUMBER = 100;
    public const POST_COMMENT_MULTIPLICATION = 10;
    public const RAND_FILE_NUMBER = 0;
    public const RAND_TITLE_POST_WORD_FROM = 3;
    public const RAND_TITLE_POST_WORD_TO = 20;
    public const RAND_DESCRIPTION_POST_PARAGRAPHS_FROM = 1;
    public const RAND_DESCRIPTION_POST_PARAGRAPHS_TO = 7;
    public const POST_TAG_NUMBER = 7;
    public const RAND_DESCRIPTION_POST_COMMENT_PARAGRAPHS_FROM = 1;
    public const RAND_DESCRIPTION_POST_COMMENT_PARAGRAPHS_TO = 1;

    private DocumentManager $dm;
    private LoremIpsum $lipsum;
    private array $tag;
    private array $post;
    private array $user;
    private int $countUser;
    private int $countTag;
    private ImageGenerateManager $igm;
    private FileDataManager $fileDataManager;
    private ParameterBagInterface $parameterBag;

    public function __construct(DocumentManager $documentManager, FileDataManager $fileDataManager, ParameterBagInterface $parameterBag)
    {
        $this->dm = $documentManager;
        $this->fileDataManager = $fileDataManager;
        $this->igm = new ImageGenerateManager();
        $this->lipsum = new LoremIpsum();
        $this->parameterBag = $parameterBag;
    }

    public function generate(int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        $this->user = $this->dm->getRepository(User::class)->findBy([], [], self::USER_NUMBER);
        $this->countUser = count($this->user);

        $this->generateTags(self::TAG_NUMBER);
        $this->countTag = count($this->tag);
        $this->generatePosts($quantity);
        $this->generatePostComments($quantity * self::POST_COMMENT_MULTIPLICATION);
    }

    private function generateTags(int $limit): void
    {
        for ($i = 0; $i < $limit; $i++) {
            $this->tag[$i] = $this->createTag();
            $this->dm->persist($this->tag[$i]);
        }

        $this->dm->flush();
    }

    private function generatePosts(int $limit): void
    {
        for ($iPost = 0; $iPost < $limit; $iPost++) {
            $this->post[$iPost] = $this->createPost();
            $this->dm->persist($this->post[$iPost]);

            for ($iFile = 0; $iFile < random_int(0, self::RAND_FILE_NUMBER); $iFile++) {
                $this->post[$iPost] = $this->addFile($this->post[$iPost], PostFile::class);
            }
        }

        $this->dm->flush();
    }

    private function generatePostComments(int $limit): void
    {
        for ($iPostComment = 0; $iPostComment < $limit; $iPostComment++) {
            $postComment[$iPostComment] = $this->createPostComment();
            $this->dm->persist($postComment[$iPostComment]);

            for ($iFile = 0; $iFile < random_int(0, self::RAND_FILE_NUMBER); $iFile++) {
                $postComment[$iPostComment] = $this->addFile($postComment[$iPostComment], PostCommentFile::class);
            }
        }

        $this->dm->flush();
    }

    private function addFile($entity, $type, $width = 800, $height = 600)
    {
        if (!in_array($type, [PostFile::class, PostCommentFile::class], true)) {
            throw new RuntimeException('Type is not valid.');
        }

        $path = $this->parameterBag->get('kernel.project_dir') . '/public/test_img.png';
        $this->igm->makeRandImage($width, $height, $path);

        $uploadedFile = new UploadedFile($path, 'test_img.png', null, null, true);
        $postFile = $this->fileDataManager->upload($uploadedFile, $type);
        $entity->addFile($postFile);

        return $entity;
    }

    private function createPost(): Post
    {
        $post = new Post();
        $post->setTitle($this->lipsum->words(random_int(self::RAND_TITLE_POST_WORD_FROM, self::RAND_TITLE_POST_WORD_TO)));
        $post->setDescription($this->lipsum->paragraphs(random_int(self::RAND_DESCRIPTION_POST_PARAGRAPHS_FROM, self::RAND_DESCRIPTION_POST_PARAGRAPHS_TO), 'p'));
        $post->setUser($this->getRandUser());
        for ($i = 0; $i < self::POST_TAG_NUMBER; $i++) {
            $post->addTag($this->tag[random_int(0, $this->countTag - 1)]);
        }

        return $post;
    }

    private function createPostComment(): PostComment
    {
        $postComment = new PostComment();
        $postComment->setDescription($this->lipsum->paragraphs(random_int(self::RAND_DESCRIPTION_POST_COMMENT_PARAGRAPHS_FROM, self::RAND_DESCRIPTION_POST_COMMENT_PARAGRAPHS_TO), 'p'));
        $postComment->setUser($this->getRandUser());
        $postComment->setPost($this->post[random_int(0, count($this->post) - 1)]);

        return $postComment;
    }

    private function createTag(): Tag
    {
        $tag = new Tag();
        $this->dm->persist($tag);
        $tag->setName($tag->getId());
        $this->dm->persist($tag);

        return $tag;
    }

    private function getRandUser(): User
    {
        $user = $this->user[random_int(0, $this->countUser - 1)];
        $this->dm->persist($user);

        return $user;
    }
}