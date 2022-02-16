<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
    /**
     * @Route("/blogs")
     */
{
    /**
     * @Route("/", name="blogs")
     */
    public function index(): Response
    {
        return $this->render('blogs/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blogs_viewBlog")
     */
    public function viewBlog($id): Response
    {
        return $this->render('blogs/blog.html.twig',[

        ]);
    }
}
