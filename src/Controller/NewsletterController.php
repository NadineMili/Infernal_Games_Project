<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Newsletter;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index(): Response
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }

    /**
     * @Route("/template", name="template1")
     */
    public function template1(): Response
    {
        $newsletter= $this->getDoctrine()->getRepository(Newsletter::class)->find(25);
        return $this->render('newsletter/index.html.twig', [
            'newsletter'=> $newsletter
        ]);
    }
}
