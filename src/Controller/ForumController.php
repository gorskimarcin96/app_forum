<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="forum_")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="index", options={"expose"=true})
     */
    public function index(): Response
    {
        return $this->render('forum/index.html.twig');
    }

    /**
     * @Route("/post/{post}", name="post", options={"expose"=true})
     * @param string $post
     * @param PostRepository $postRepository
     * @return Response
     * @throws LockException
     * @throws MappingException
     */
    public function post(string $post, PostRepository $postRepository): Response
    {
        return $this->render('forum/post.html.twig', [
            'post' => $postRepository->find($post)
        ]);
    }
}
