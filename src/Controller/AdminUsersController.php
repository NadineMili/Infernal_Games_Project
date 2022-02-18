<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        return $this->render('admin_users/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }


    /**
     * @Route("/new", name="admin_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request) : Response
    {
        $user = new User();
        $form =$this->createForm(UserType::class, $user );
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin_users/new.html.twig', [
            'user' =>$user,
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_users_edit", methods={"GET", "POST"})
     */
    public function edit(UserRepository $repository,$id, Request $request, EntityManagerInterface $em): Response
    {
        $users = $repository ->find($id);
        $form = $this -> createForm(UserType::class, $users);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this ->redirectToRoute('admin_users');
        }
        return $this->render('admin_users/new.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function delete($id,Request $request, UserRepository $repository, EntityManagerInterface $em): Response
    {
        $users = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($users);
        $em->flush();
        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
