<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;


class CategoryController extends AbstractController

/**
     * @Route("/category")
     */
{
    /**
     * @Route("/", name="category_activity_index")
     */
    public function index(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();
        return $this->render('category_activity/index.html.twig', [
            'categories' => $categories,
       
        ]);
    }

    /**
     * @Route("/new", name="category_activity_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_activity_index');
        }
        return $this->render("category_activity/new.html.twig",[
            'category' =>$category,
            'form' => $form->createView()
        ]);
}
 /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
public function show(Category $category): Response
{
    return $this->render('category_activity/show.html.twig', [
        'category_activity' => $category,
    ]);
}

/**
     * @Route("/edit/{id}", name="category_activity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category ,CategoryRepository $repo, EntityManagerInterface $entityManager,int $id): Response
   
    {
        $category = $repo->find($id);
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
       
    
        if($form->isSubmitted() && $form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute("category_activity_index");
        
            }
        
        return $this->render("category_activity/new.html.twig", [
            'category_activity' =>$category,
               'form' => $form->createView()
        ]);
    }

      /**
     * @Route("/delete/{id}", name="category_activity_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, $id , CategoryRepository $repo): Response
    {

        $category = $repo->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
     
        return $this->redirectToRoute("category_activity_index");
    }
}

