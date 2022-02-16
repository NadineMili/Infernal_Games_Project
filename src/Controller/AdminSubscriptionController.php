<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSubscriptionController extends AbstractController
    /**
     * @Route("/admin/subscription", name="admin_subscription")
     */
{
    /**
     * @Route("/", name="admin_subscription")
     */
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('admin_subscription/index.html.twig', [
            'subscriptions' => $subscriptionRepository->findAll(),
        ]);
    }

    public function subPlans(){

    }

}
