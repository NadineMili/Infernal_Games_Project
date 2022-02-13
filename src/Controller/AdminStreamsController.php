<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStreamsController extends AbstractController
{
    /**
     * @Route("/admin/streams", name="admin_streams")
     */
    public function index(): Response
    {
        return $this->render('admin_streams/index.html.twig', [
            'controller_name' => 'AdminStreamsController',
        ]);
    }
}
