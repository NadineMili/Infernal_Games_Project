<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route({"/","/home"}, name="home")
     */
    public function index(GameRepository $gameRepository): Response
    {
        $currentUser= $this->getUser();
        $game= $gameRepository->findHighestRated();



        return $this->render('home/index.html.twig', [
            'currentUser' => $currentUser,
                'game'=>$game[0][0],
        ]);
    }

    /**
     * @Route("/subscribeToNewsletter", name="subscribeToNewsletter")
     */
    public function subscribeToNewsletter(EntityManagerInterface $manager): Response
    {
        $currentUser= $this->getUser();
        if ($currentUser){
            $subscription= $currentUser->getSubscription();
            if (!$subscription){
                $subscription = new Subscription();
                $subscription->setUser($currentUser);
            }
            $subscription->setStatus(true);

            $manager->persist($subscription);
            $manager->flush();

        }

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/unSubscribeToNewsletter", name="unSubscribeToNewsletter")
     */
    public function unSubscribeToNewsletter(EntityManagerInterface $manager): Response
    {
        $currentUser= $this->getUser();
        if ($currentUser){
            $subscription= $currentUser->getSubscription();
            if (!$subscription){
                $subscription = new Subscription();
                $subscription->setUser($currentUser);
            }
            $subscription->setStatus(false);

            $manager->persist($subscription);
            $manager->flush();

        }
        return $this->redirectToRoute('home');
    }
}
