<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function index(): Response
    {
        return $this->render('admin_products/index.html.twig', [
            'controller_name' => 'AdminProductsController',
        ]);
    }
}
