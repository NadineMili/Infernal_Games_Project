<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamsController extends AbstractController
{
    /**
     * @Route("/streams", name="streams")
     */
    public function index(): Response
    {
        return $this->render('streams/index.html.twig', [
            'controller_name' => 'StreamsController',
        ]);
    }
}
