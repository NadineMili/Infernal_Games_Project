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
     * @Route("/template1", name="template1")
     */
    public function template1(): Response
    {
        $newsletter= new Newsletter();
        $newsletter->setTitle("Title");
        $newsletter->setContent("Content");
        return $this->render('newsletter/index.html.twig', [
            'newsletter'=> $newsletter
        ]);
    }
}
