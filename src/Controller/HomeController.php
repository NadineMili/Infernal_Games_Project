<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Repository\AdRepository;
use App\Repository\BlogRepository;
use App\Repository\GameRepository;
use App\Repository\ProductRepository;
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
    public function index(BlogRepository $blogRepository, ProductRepository $productRepository,GameRepository $gameRepository,AdRepository $adRepository): Response
    {
        $currentUser= $this->getUser();
        $game= $gameRepository->findHighestRated();
        $product = $productRepository->findBy(array(),['date'=>'ASC']);
        $blogsAll= $blogRepository->findAll();

        if (count($blogsAll)<2){
            $blogs= array_slice($blogsAll,0, 1);
        }elseif (count($blogsAll)<3){
            $blogs= array_slice($blogsAll,0, 2);
        }elseif (count($blogsAll)>=3){
            $blogs= array_slice($blogsAll,0, 3);
        }

        return $this->render('home/index.html.twig', [
            'currentUser' => $currentUser,
                'game'=>$game[0][0],
            'ads'=> $adRepository->findBy(['etat'=>true]),
            'product'=>$product[0],
            'blogs'=>$blogs
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

    /**
     * @Route("/info", name="info")
     */
    public function infoPage(){
        return $this->render('home/info.html.twig', []);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactPage(){
        return $this->render('home/contact.html.twig', []);
    }
}
