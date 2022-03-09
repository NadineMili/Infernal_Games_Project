<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameComment;
use App\Entity\Publication;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\GamesType;
use App\Repository\GameCommentRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        return $this->render('games/index.html.twig', [
            'controller_name' => 'GamesController',
            'games' => $games
        ]);
    }

    /**
     * @Route("/gameCommentsAjax/{id}", name="gameCommentsAjax")
     */
    public function gameCommentsAjax($id, Request $req, NormalizerInterface $normalizer){
        $comments= $this->getDoctrine()->getRepository(GameComment::class)->findBy(['game'=>$id]);
        $jsonData=$normalizer->normalize($comments, 'json', ['groups'=>'gameComments:read']);

        $i=0;
        foreach ($comments as $comment){
            $jsonData[$i++]['user']= $comment->getUser()->getUsername();
        }
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/view/{id}", name="view_game")
     */
    public function viewGame($id, Request $request, EntityManagerInterface $em,
                             UserRepository $userRepository,
                             GameRepository $gameRepository,NormalizerInterface $normalizer)
    {
        #$user=$this->getUser();

        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
        $comments= $this->getDoctrine()->getRepository(GameComment::class)->findBy(['game'=>$game]);
        #$comments = $this->getDoctrine()->getRepository(GameComment::class)->findAll();
        #$comment = new GameComment();

        #$form = $this->createForm(CommentType::class, $comment);
        #$form->handleRequest($request);
        #if ($form->isSubmitted() && $form->isValid()) {
            #$em->persist($comment);
            #$em->flush();

            #return $this->redirectToRoute('view_game', ['id' => $id]);
        #}
        /*
        $em = $this->getDoctrine()->getManager();

        $user = $userRepository->find($request->get('user'));
        $gameC = $gameRepository->find($request->get('game'));

        $description = $request->get('description');

        $comment = new GameComment();
        $comment->setUser($user);
        $comment->setGame($gameC);
        $comment->setDescription($description);

        $em->persist($comment);
        $em->flush();
        $jsonData=$normalizer->normalize($comment,'json',['groups'=>'comments:read']);

        return new Response(json_encode($jsonData));
        */
        return $this->render('games/game.html.twig',
            ['game' => $game, 'comments'=>$comments, 'users'=>$userRepository->findAll()]);

    }

    /**
     * @Route("/addGameComment", name="addGameComment")
     */
    public function addGameComment( Request $request, EntityManagerInterface $em,
                             UserRepository $userRepository,
                             GameRepository $gameRepository,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $userRepository->find($request->get('user'));
        $game = $gameRepository->find($request->get('game'));
        $description = $request->get('description');

        $comment = new GameComment();
        $comment->setUser($user);
        $comment->setGame($game);
        $comment->setDescription($description);

        $em->persist($comment);
        $em->flush();
        $jsonData=$normalizer->normalize($comment,'json',['groups'=>'gameComments:read']);

        return new Response(json_encode($jsonData));


    }

    /**
     * @Route("/view/{id}", name="comment_edit", methods={"GET", "POST"})
     */
    public function edit(GameCommentRepository $repository, $id, Request $request, EntityManagerInterface $em): Response
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
     * @Route("/view/{id}/delete", name="comment_delete")
     */
    public function delete( EntityManagerInterface $em, $idCommentaire): Response
    {
        $em=$this->getDoctrine()->getManager();
        $comments = $em->getRepository(GameComment::class)->find($idCommentaire);
        $em->remove($comments);
        $em->flush();
        return new Response(null);
    }



}