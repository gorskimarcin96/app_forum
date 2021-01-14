<?php

namespace App\Controller;

use App\Entity\Post;
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
     * @param Post $post
     * @return Response
     */
    public function post(Post $post): Response
    {
        return $this->render('forum/post.html.twig', [
            'post' => $post
        ]);
    }
}
