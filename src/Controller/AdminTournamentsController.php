<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTournamentsController extends AbstractController
{
    /**
     * @Route("/admin/tournaments", name="admin_tournaments")
     */
    public function index(): Response
    {
        return $this->render('admin_tournaments/index.html.twig', [
            'controller_name' => 'AdminTournamentsController',
        ]);
    }
}
