<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostCommentRepository;
use App\Repository\PostRepository;
use App\Utils\ForumDataManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ExceptionInterface
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
     */
    public function postCommentAdd(Request $request, SerializerInterface $serializer, ForumDataManager $manager): JsonResponse
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
     * @Route("/post/{post}", name="post", methods={"GET"}, requirements={"post"="\d+"})
     * @param Post $post
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function post(Post $post, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($post, 'json', ['groups' => ['post', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
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
    public function postGet(int $page, Request $request, SerializerInterface $serializer, PostRepository $postRepository): JsonResponse
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
     * @param Post $post
     * @param int $page
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param PostCommentRepository $postCommentRepository
     * @return JsonResponse
     */
    public function postCommentGet(Post $post, int $page, Request $request, SerializerInterface $serializer, PostCommentRepository $postCommentRepository): JsonResponse
    {
        $data = $postCommentRepository->page(
            $post,
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
