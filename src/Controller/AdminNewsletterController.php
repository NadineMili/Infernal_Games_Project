<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\AdminRepository;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class AdminNewsletterController extends AbstractController
    /**
     * @Route("/admin/newsletter")
     */
{
    /**
     * @Route("/", name="admin_newsletter")
     */
    public function index(NewsletterRepository $newsletterRepository): Response
    {
        return $this->render('admin_newsletter/index.html.twig', [
            'newsletters' => $newsletterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_newsletter_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, AdminRepository $adminRepository): Response{
        $newsletter= new Newsletter();
        $form=$this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // To change later
            $newsletter->setAuthor( $adminRepository->find(1) );

            // Get local date
            $date= new \DateTime('now');
            $newsletter->setDate($date);
            $entityManager->persist($newsletter);
            $entityManager->flush();
            return $this->redirectToRoute('admin_newsletter', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_newsletter/new.html.twig',[
            'newsletter'=> $newsletter,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_newsletter_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        return $this->render('admin_newsletter/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_newsletter_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_games', [], Response::HTTP_SEE_OTHER);
    }
}
