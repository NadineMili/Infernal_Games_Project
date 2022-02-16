<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTournamentsController extends AbstractController
    /**
     * @Route("/admin/tournaments")
     */
{
    /**
     * @Route("/", name="admin_tournaments")
     */
    public function index(): Response
    {
        return $this->render('admin_tournaments/index.html.twig', [
            'controller_name' => 'AdminTournamentsController',
        ]);
    }

    /**
     * @Route("/new", name="admin_tournaments_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        return $this->render('admin_tournaments/new.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_tournaments_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_tournaments/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_tournaments_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_tournaments', [], Response::HTTP_SEE_OTHER);
    }
}
