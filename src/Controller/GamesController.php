<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameComment;
use App\Form\CommentType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function viewGame($id, Request $request, EntityManagerInterface $em){
        $game=$this->getDoctrine()->getRepository(Game::class)->find($id);
        $comments=$this->getDoctrine()->getRepository(GameComment::class)->findAll();
        $comment = new GameComment();
        $form =$this->createForm(CommentType::class, $comment);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('view_game',['id' => $id]);
        }
        return $this->render('games/game.html.twig',
            ['game'=>$game , 'comments' => $comments, 'form' => $form -> createView()] );
    }



}