<?php

namespace App\Controller\Api;

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
     * @Route("/post/get/{page}", name="post_get", methods={"GET"}, defaults={"page"=1})
     * @param int $page
     * @param SerializerInterface $serializer
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function postGet(int $page, SerializerInterface $serializer, PostRepository $postRepository): JsonResponse
    {
        $json = $serializer->serialize($postRepository->page($page), 'json', ['groups' => ['post', 'user', 'file']]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
