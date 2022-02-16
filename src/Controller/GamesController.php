<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
    /**
     * @Route("/games")
     */
{
    /**
     * @Route("/", name="games")
     */
    public function index(): Response
    {
        $games=$this->getDoctrine()->getRepository(Game::class)->findAll();
        return $this->render('games/index.html.twig', [
            'controller_name' => 'GamesController',
            'games' => $games
        ]);
    }

    /**
     * @Route("/view/{id}", name="view_game")
     */
    public function viewGame($id){
        $game=$this->getDoctrine()->getRepository(Game::class)->find($id);

        return $this->render('games/game.html.twig',
            ['game'=>$game]);
    }
}
