<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\BlogRepository;
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
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blogs/index.html.twig', [
            'blogs'=> $blogRepository->findAll()
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blogs_viewBlog")
     */
    public function viewBlog($id, BlogRepository $blogRepository, AdRepository $adRepository): Response
    {
        return $this->render('blogs/blog.html.twig',[
            'blog'=>$blogRepository->find($id),
            'ads'=> $adRepository->findBy(['etat'=>true])
        ]);
    }
}
