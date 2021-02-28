<?php

namespace App\Controller\Api;

use App\Document\Post;
use App\Repository\PostCommentRepository;
use App\Repository\PostRepository;
use App\Utils\ForumDataManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/forum", name="forum_")
 */
class ApiForumController extends AbstractController
{
    public const DEFAULT_LIMIT = 10;

    /**
     * @Route("/post/add", name="post_add", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ForumDataManager $manager
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws MongoDBException
     */
     public function postAdd(Request $request, SerializerInterface $serializer, ForumDataManager $manager): JsonResponse
    {
        $post = $manager->postCreate(
            json_decode($request->get('post'), true),
            $this->getUser(),
            explode(',', $request->get('tags')),
            $request->files->get('files')
        );
        $json = $serializer->serialize($post, 'json', ['groups' => ['post', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/post-comment/add", name="post_comment_add", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ForumDataManager $manager
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws MongoDBException
     */
    public function postCommentAdd(
        Request $request,
        SerializerInterface $serializer,
        ForumDataManager $manager
    ): JsonResponse
    {
        $postComment = $manager->postCommentCreate(
            json_decode($request->get('postComment'), true),
            $this->getUser(),
            $request->files->get('files')
        );
        $json = $serializer->serialize($postComment, 'json', ['groups' => ['post_comment', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/post/id/{post}", name="post", methods={"GET"})
     * @param string $post
     * @param DocumentManager $documentManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function post(string $post, DocumentManager $documentManager, SerializerInterface $serializer): JsonResponse
    {
        $post = $documentManager->find(Post::class, $post);
        $json = $serializer->serialize($post, 'json', ['groups' => ['post', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/post/get/types", name="post_get_types", methods={"GET"})
     */
    public function postGetTypes(): JsonResponse
    {
        return new JsonResponse(PostRepository::ORDER_BY_TYPES, Response::HTTP_OK);
    }

    /**
     * @Route("/post/get/{page}", name="post_get", methods={"GET"}, defaults={"page"=1})
     * @param int $page
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function postGet(
        int $page,
        Request $request,
        SerializerInterface $serializer,
        PostRepository $postRepository
    ): JsonResponse
    {
        $data = $postRepository->page(
            $page,
            $request->get('limit', self::DEFAULT_LIMIT),
            $request->get('type', PostRepository::ORDER_BY_TYPES[0])
        );
        $json = $serializer->serialize($data, 'json', ['groups' => ['post', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/post-comment/get/{post}/{page}", name="post_comment_get", methods={"GET"}, defaults={"page"=1})
     * @param string $post
     * @param int $page
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param PostCommentRepository $postCommentRepository
     * @param PostRepository $postRepository
     * @return JsonResponse
     * @throws LockException
     * @throws MappingException
     * @throws MongoDBException
     */
    public function postCommentGet(
        string $post,
        int $page,
        Request $request,
        SerializerInterface $serializer,
        PostCommentRepository $postCommentRepository,
        PostRepository $postRepository
    ): JsonResponse
    {
        $data = $postCommentRepository->page(
            $postRepository->find($post),
            $page,
            $request->get('limit', self::DEFAULT_LIMIT)
        );
        $json = $serializer->serialize($data, 'json', ['groups' => ['post_comment', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/sidebar-nav", name="sidebar_nav", methods={"GET"})
     */
    public function sidebarNav(): JsonResponse
    {
        return new JsonResponse(PostRepository::ORDER_BY_TYPES, Response::HTTP_OK);
    }
}
