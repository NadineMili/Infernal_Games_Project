<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminNewsletterController extends AbstractController
{
    /**
     * @Route("/admin/newsletter", name="admin_newsletter")
     */
    public function index(): Response
    {
        return $this->render('admin_newsletter/index.html.twig', [
            'controller_name' => 'AdminNewsletterController',
        ]);
    }
}
