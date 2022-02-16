<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBlogController extends AbstractController
    /**
     * @Route("/admin/blogs")
     */
{
    /**
     * @Route("/", name="admin_blogs")
     */
    public function index(): Response
    {
        return $this->render('admin_blogs/index.html.twig', [
            'controller_name' => 'AdminBlogController',
        ]);
    }

    /**
     * @Route("/new", name="admin_blogs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        return $this->render('admin_blogs/new.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_blogs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_blogs/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_blogs_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_blogs', [], Response::HTTP_SEE_OTHER);
    }
}
