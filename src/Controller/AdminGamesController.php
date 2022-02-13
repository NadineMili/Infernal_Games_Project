<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGamesController extends AbstractController
    /**
     * @Route("/admin/games")
     */
{
    /**
     * @Route("/", name="admin_games")
     */
    public function index(): Response
    {
        return $this->render('admin_games/index.html.twig', [
            'controller_name' => 'AdminGamesController',
        ]);
    }

    /**
     * @Route("/new", name="admin_games_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        return $this->render('admin_games/new.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="admin_games_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_games/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_games_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_games', [], Response::HTTP_SEE_OTHER);
    }
}
