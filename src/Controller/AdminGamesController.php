<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;
use App\Entity\Game;
use App\Form\GamesType;

class AdminGamesController extends AbstractController
    /**
     * @Route("/admin/games")
     */
{
    /**
     * @Route("/", name="admin_games")
     */
    public function index(GameRepository $repository): Response
    {
        return $this->render('admin_games/index.html.twig', [
            'games' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/newGame", name="admin_games_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em) : Response
    {
        $game = new Game();
        $form =$this->createForm(GamesType::class, $game);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em->persist($game);
            $em->flush();
            return $this->redirectToRoute('admin_games');
        }
        return $this->render('admin_games/new.html.twig', [
            'form' => $form -> createView()
        ]);
        }

    /**
     * @Route("/edit/{id}", name="admin_games_edit", methods={"GET", "POST"})
     */
    public function edit(GameRepository $repository,$id, Request $request, EntityManagerInterface $em): Response
    {
        $games = $repository ->find($id);
        $form = $this -> createForm(GamesType::class, $games);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this ->redirectToRoute('admin_games');
        }
        return $this->render('admin_games/new.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_games_delete")
     */
    public function delete($id,Request $request, GameRepository $repository, EntityManagerInterface $em): Response
    {
        $games = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($games);
        $em->flush();
        return $this->redirectToRoute('admin_games', [], Response::HTTP_SEE_OTHER);
    }
}
