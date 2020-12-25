<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="forum_")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('forum/index.html.twig');
    }

    /**
     * @Route("/post/add", name="post_add")
     */
    public function postAdd(): Response
    {
        return $this->render('forum/post_add.html.twig');
    }
}
