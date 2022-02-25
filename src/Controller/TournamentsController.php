<?php

namespace App\Controller;

use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentsController extends AbstractController
    /**
     * @Route("/tournaments")
     */
{
    /**
     * @Route("/", name="tournaments")
     */
    public function index(): Response
    {
        $tournaments=$this->getDoctrine()->getRepository(Tournament::class)->findAll();
        return $this->render('tournaments/index.html.twig', [
            'controller_name' => 'TournamentsController',
            'tournaments' => $tournaments
        ]);
    }

    /**
     * @Route("/view/{id}", name="view_tournament")
     */
    public function viewTournament($id){
        $tournament=$this->getDoctrine()->getRepository(tournament::class)->find($id);

        return $this->render('tournaments/tournaments.html.twig',
            ['tournament'=>$tournament]);
    }
}