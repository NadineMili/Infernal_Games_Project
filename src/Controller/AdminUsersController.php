<?php

namespace App\Controller;

use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;

class AdminUsersController extends AbstractController
    /**
     * @Route("/admin/users")
     */
{
    /**
     * @Route("/", name="admin_users")
     */
    public function index(UserRepository$repo): Response
    {
        $users = $repo->findAll();
        return $this->render('admin_users/index.html.twig', [
            "users" =>$users,
        ]);
    }


    /**
     * @Route("/new", name="admin_users_new", methods={"GET", "POST"})
     */
    public function addUser(Request $request ): Response{
        $user= new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute("admin_users");

        }

        return $this->render("admin_users/new.html.twig",[
            'user' =>$user,
            'form' => $form->createView()
        ]);
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
