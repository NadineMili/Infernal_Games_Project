<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TournamentRepository;
use App\Entity\Tournament;
use App\Form\TournamentType;  

class AdminTournamentsController extends AbstractController
    /**
     * @Route("/admin/tournaments")
     */
{
    /**
     * @Route("/", name="admin_tournaments")
     */
    public function index(TournamentRepository $repository): Response
    {
        return $this->render('admin_tournaments/index.html.twig', [
            'tournaments' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="admin_tournaments_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em ,tournament $tournament = null): Response
    {
        if (!$tournament){ 
        $tournament = new tournament();}
        $form =$this->createForm(TournamentType::class, $tournament);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
           
            $em->persist($tournament);
            $em->flush();
            return $this->redirectToRoute('admin_tournaments');
        }
        return $this->render('admin_tournaments/new.html.twig', [
            'form' => $form -> createView()
        ]);
    
    }

    /**
     * @Route("/edit/{id}", name="admin_tournaments_edit", methods={"GET", "POST"})
     */
    public function edit(TournamentRepository $TournamentRepository , $id , Request $request, EntityManagerInterface $em): Response
    {
        $tournaments = $TournamentRepository ->find($id);
        $form = $this -> createForm(TournamentType::class, $tournaments);
        $form -> handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournaments);
            $em->flush();
            return $this ->redirectToRoute('admin_tournaments');
        }
        return $this->render('admin_tournaments/new.html.twig',[
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_tournaments_delete")
     */
    public function delete(TournamentRepository $TournamentRepository , $id ,Request $request, EntityManagerInterface $em): Response
    {
        $TournamentRepository = $TournamentRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($TournamentRepository);
        $em->flush();
        return $this->redirectToRoute('admin_tournaments', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route ("/search" ,name="search")
     */
    function search (TournamentRepository $TournamentRepository, Request $request) {
        $data = $request -> get('search');
        $tournament = $TournamentRepository ->findBy( ['name'=> $data]);
        return $this -> render('admin_tournaments/index.html.twig' ,[
                'tournaments' => $tournament
            ]
        );


    }
}