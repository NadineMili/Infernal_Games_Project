<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameComment;
use App\Form\CommentType;
use App\Form\GamesType;
use App\Repository\GameCommentRepository;
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

    /**
     * @Route("/view/{id}", name="comment_edit", methods={"GET", "POST"})
     */
    public function edit(GameCommentRepository $repository,$id, Request $request, EntityManagerInterface $em): Response
    {
        $comments = $repository ->find($id);
        $form = $this -> createForm(CommentType::class, $comments);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this ->redirectToRoute('view_game');
        }
        return $this->render('games/game.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/view/{id}", name="comment_delete")
     */
    public function delete($id,Request $request, GameCommentRepository $repository, EntityManagerInterface $em): Response
    {
        $comments = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($comments);
        $em->flush();
        return $this->redirectToRoute('view_game');
    }
}