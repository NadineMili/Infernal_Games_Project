<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Form\CommandeType;

class CommandeController extends AbstractController
 /**
     * @Route("/commandes")
     */
{
    /**
     * @Route("/", name="commande")
     */
    public function index(CommandeRepository $repo): Response
    {
        $commandes = $repo->findAll();

        return $this->render('commande/index.html.twig',[
            "commandes" =>$commandes,
        ]);
    }
/**
 * * @Route("/add", name="command_add")
 */
    public function addCommande(Request $request){
        $commande= new Commande();
        $form = $this->createForm(CommandeType::class,$commande);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
         
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            return $this->redirectToRoute("shop");

        }
        
        return $this->render("shop/commande.html.twig",[
            'commande' =>$commande,
            'form' => $form->createView()
        ]);
    }
}
