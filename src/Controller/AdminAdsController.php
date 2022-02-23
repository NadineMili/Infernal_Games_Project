<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ads")
 */
class AdminAdsController extends AbstractController
{
    /**
     * @Route("/", name="admin_ads", methods={"GET"})
     */
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('admin_ads/index.html.twig', [
            'ads' => $adRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="admin_ads_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SponsorRepository $sponsorRepository): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ads', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_ads/new.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="admin_ads_show", methods={"GET"})
     */
    public function show(Ad $ad): Response
    {
        return $this->render('admin_ads/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_ads_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ad $ad, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_ads', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_ads/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_ads_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, $id, AdRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $ad = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($ad);
        $em->flush();
        return $this->redirectToRoute('admin_ads', [], Response::HTTP_SEE_OTHER);
        
    }
}
