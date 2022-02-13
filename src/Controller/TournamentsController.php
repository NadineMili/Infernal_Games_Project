<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentsController extends AbstractController
{
    /**
     * @Route("/tournaments", name="tournaments")
     */
    public function index(): Response
    {
        return $this->render('tournaments/index.html.twig', [
            'controller_name' => 'TournamentsController',
        ]);
    }
}
