<?php

namespace App\Controller;

use App\Repository\PostRepository;
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
     */
    public function post(string $post, PostRepository $postRepository): Response
    {
        return $this->render('forum/post.html.twig', [
            'post' => $postRepository->find($post)
        ]);
    }
}
