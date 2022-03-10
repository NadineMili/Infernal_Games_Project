<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameComment;
use App\Entity\Likes;
use App\Entity\Publication;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\GamesType;
use App\Repository\GameCommentRepository;
use App\Repository\GameRepository;
use App\Repository\RatingRepository;
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
     * @Route("/gameRatingAjax/{id}", name="gameRatingAjax")
     */
    public function gameRatingAjax($id, Request $req, NormalizerInterface $normalizer){

        $rates= $this->getDoctrine()->getRepository(Rating::class)->findBy(['rate'=>$id]);
        $jsonData=$normalizer->normalize($rates, 'json', ['groups'=>'gameRatings:read']);

        $i=0;
        foreach ($rates as $rate){
            $jsonData[$i++]['user']= $rate->getUser()->getUsername();
        }
        return new Response(json_encode($jsonData));
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
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
        $comments= $this->getDoctrine()->getRepository(GameComment::class)->findBy(['game'=>$game]);

        $userRating= $this->getDoctrine()->getRepository(Rating::class)->findOneBy(['user'=>3]);
        $gameRating= $this->getDoctrine()->getRepository(Rating::class)->getAvrGameRating($id);
        if ($userRating){
            $r= $userRating->getUserRating();
        }else{
            $r=0;
        }
        return $this->render('games/game.html.twig',
            ['game' => $game, 'comments'=>$comments,
                'users'=>$userRepository->findAll(),
                'gameRating'=>$gameRating[0]['avgGameRating'],
                'userRating'=>(string) $r
            ]);

    }

    /**
     * @Route("/addGameRating", name="addGameRating")
     */
    public function addGameRating( Request $request, EntityManagerInterface $em,
                                   UserRepository $userRepository,
                                   GameRepository $gameRepository,
                                   RatingRepository $ratingRepository,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $userRepository->find($request->get('user'));
        $game = $gameRepository->find($request->get('game'));
        $rating= $request->get('rate');

        $rate= $ratingRepository->findOneBy(['user'=>$user->getId()]);
        if(!$rate){
            $rate = new Rating();
            $rate->setUser($user);
            $rate->setGame($game);

        }
        $rate->setUserRating($rating);
        //$rate->setRateIndex($rate);

        $em->persist($rate);
        $em->flush();
        $jsonData=$normalizer->normalize($rate,'json',['groups'=>'gameRating:read']);

        return new Response(json_encode($jsonData));


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

    function addLike($haveLike, $typeLike, $idUser, $comments)
    {

        if ($haveLike == null) {
            $like = new Likes();
            $like->setTypeLike($typeLike);
            $comment = $this->getDoctrine()->getManager()->getRepository(GameComment::class)->find($comments);

            $like->setComment($comment);
            $like->setIdUser($idUser);

            $repository = $this->getDoctrine()->getManager();
            $repository->persist($like);
            $repository->flush();

        } else {
            $missionManager = $this->getDoctrine()->getManager();
            $missionManager->remove($haveLike);
            $missionManager->flush();
        }
    }

}