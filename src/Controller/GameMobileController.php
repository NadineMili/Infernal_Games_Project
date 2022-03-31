<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use function PHPUnit\Framework\never;


class GameMobileController extends AbstractController
    /**
     * @Route("/game/mobile", name="mobile_game")
     */
{


    /**
     * @Route("/all", name="getAllGames")
     */
    public function allGames(NormalizerInterface $normalizer)
    {
        $games = $this->getDoctrine()->getManager()->getRepository(Game::class)->findALL();
        $jsonData=$normalizer->normalize($games, 'json', ['groups'=>'games:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/ajout", name="add_new")
     */

    public function ajouterGame (Request $request, NormalizerInterface $normalizer){
        $game = new Game();
        $name= $request->query->get("name");
        $description= $request->query->get("description");
        $price= $request->query->get("price");
        $trailerUrl= $request->query->get("trailerUrl");
        $category= $request->query->get("category");
        $rating= $request->query->get("rating");
        $picture= $request->query->get("picture");

        $em = $this->getDoctrine()->getManager ();
        $game->setName ($name);
        $game->setDescription($description);
        $game->setPrice($price);
        $game->setTrailerUrl( $trailerUrl);
        $game->setCategory( $category);
        $game->setRating( $rating);
        $game->setPicture($picture);

        $em->persist ($game);
        $em->flush();
        $jsonData=$normalizer->normalize($game, 'json', ['groups'=>'games:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/delete", name="delete")
     */

    public function deleteGame (Request $request) {
        $em = $this->getDoctrine()->getManager ();
        $game = $em->getRepository( Game::class)->find($request->get("id"));
        $em->remove ($game);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/update", name="modifier_game")
     */

    public function modifierGame(Request $request, NormalizerInterface $normalizer) {
        $em = $this->getDoctrine()->getManager();
        $game = $this->getDoctrine()->getManager ()
            ->getRepository(  Game::class)
            ->find($request->get("id"));
        $game->setName($request->get("name"));
        $game->setDescription($request->get("description"));
        $game->setPrice($request->get("price"));
        $game->setTrailerUrl( $request->get("trailerUrl"));
        $game->setCategory( $request->get("category"));
        $game->setRating( $request->get("rating"));
        $game->setPicture($request->get("picture"));
        $em->persist ($game);
        $em->flush();

        $jsonData=$normalizer->normalize($game, 'json', ['groups'=>'games:read']);
        return new Response(json_encode($jsonData));
    }
}
