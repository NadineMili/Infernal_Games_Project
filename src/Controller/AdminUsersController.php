<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
    /**
     * @Route("/admin/users")
     */
{
    /**
     * @Route("/", name="admin_users")
     */
    public function index(): Response
    {
        return $this->render('admin_users/index.html.twig', [
            'controller_name' => 'AdminUsersController',
        ]);
    }


    /**
     * @Route("/new", name="admin_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        return $this->render('admin_games/new.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="admin_users_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_users/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="admin_users_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
