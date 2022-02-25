<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
    /**
     * @Route("/admin/products")
     */
{
    /**
     * @Route("/", name="admin_products")
     */
    public function index(): Response
    {
        return $this->render('admin_products/index.html.twig', [
            'controller_name' => 'AdminProductsController',
        ]);
    }

    /**
     * @Route("/new", name="admin_products_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_products/new.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_products__edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_products/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_products__delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_products', [], Response::HTTP_SEE_OTHER);
    }
}
