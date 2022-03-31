<?php

namespace App\Controller;

use App\Entity\StreamCategory;
use App\Entity\StreamRating;
use App\Form\StreamCategoryType;
use App\Form\StreamRatingType;
use App\Repository\StreamCategoryRepository;
use App\Repository\StreamRatingRepository;
use App\Repository\StreamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminStreamsController extends AbstractController
    /**
     * @Route("/admin/streams")
     */
{
    /**
     * @Route("/", name="admin_streams")
     */
    public function index(StreamRepository $rep): Response
    {
        return $this->render('admin_streams/index.html.twig', [
            'controller_name' => 'AdminStreamsController',
            'streams'=> $rep->findAll()
        ]);
    }

    //Categories
    /**
     * @Route("/categories", name="admin_streams_categories")
     */
    public function showCategories(StreamCategoryRepository $rep): Response
    {
        return $this->render('admin_streams/categories.html.twig', [
            'controller_name' => 'AdminStreamsController',
            'categories'=> $rep->findAll()
        ]);
    }

    /**
     * @Route("/categories/new", name="admin_streams_categories_new", methods={"GET", "POST"})
     */
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response{
        $category= new StreamCategory();
        $form=$this->createForm(StreamCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($category);
            $entityManager->flush();


            return $this->redirectToRoute('admin_streams_categories', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_streams/newCategory.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/edit/{id}", name="admin_streams_categories_edit", methods={"GET", "POST"})
     */
    public function editCategory(Request $request,StreamCategoryRepository $screp, EntityManagerInterface $entityManager, $id): Response{
        $category= $screp->find($id);
        $form=$this->createForm(StreamCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($category);
            $entityManager->flush();


            return $this->redirectToRoute('admin_streams_categories', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_streams/newCategory.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("categories/delete/{id}", name="admin_streams_categories_delete")
     */
    public function deleteCategory(Request $request, EntityManagerInterface $entityManager,$id, StreamCategoryRepository $rep): Response
    {
        $category= $rep->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('admin_streams_categories', [], Response::HTTP_SEE_OTHER);
    }

    //Ratings
    /**
     * @Route("/ratings", name="admin_streams_ratings")
     */
    public function showRatings(StreamRatingRepository $rep): Response
    {
        return $this->render('admin_streams/ratings.html.twig', [
            'controller_name' => 'AdminStreamsController',
            'ratings'=> $rep->findAll()
        ]);
    }

    /**
     * @Route("/ratings/new", name="admin_streams_ratings_new", methods={"GET", "POST"})
     */
    public function newRating(Request $request, EntityManagerInterface $entityManager): Response{
        $rating= new StreamRating();
        $form=$this->createForm(StreamRatingType::class, $rating);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($rating);
            $entityManager->flush();


            return $this->redirectToRoute('admin_streams_ratings', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_streams/newRating.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ratings/edit/{id}", name="admin_streams_ratings_edit", methods={"GET", "POST"})
     */
    public function editRating(Request $request,StreamRatingRepository $srrep, EntityManagerInterface $entityManager, $id): Response{
        $rating= $srrep->find($id);
        $form=$this->createForm(StreamRatingType::class, $rating);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($rating);
            $entityManager->flush();


            return $this->redirectToRoute('admin_streams_ratings', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_streams/newRating.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("ratings/delete/{id}", name="admin_streams_ratings_delete")
     */
    public function deleteRating(Request $request, EntityManagerInterface $entityManager,$id, StreamRatingRepository $rep): Response
    {
        $rating= $rep->find($id);
        $entityManager->remove($rating);
        $entityManager->flush();
        return $this->redirectToRoute('admin_streams_ratings', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete/{id}", name="admin_streams_delete")
     */
    public function deleteStream(Request $request, EntityManagerInterface $entityManager,$id, StreamRepository $rep): Response
    {
        $stream= $rep->find($id);
        $entityManager->remove($stream);
        $entityManager->flush();
        return $this->redirectToRoute('admin_streams', [], Response::HTTP_SEE_OTHER);
    }
}
