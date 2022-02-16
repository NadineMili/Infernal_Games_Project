<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class ShopController extends AbstractController
    /**
     * @Route("/shop")
     */
{
    /**
     * @Route("/", name="shop")
     */
    public function index(): Response
    {
        $products= $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'products'=>$products
        ]);
    }

    /**
     * @Route("/view/{id}", name="view_product")
     */
    public function viewGame($id){
        $product=$this->getDoctrine()->getRepository(Product::class)->find($id);

        return $this->render('shop/product.html.twig',
            ['product'=>$product]);
    }

    /**
     * @Route("/cart", name="view_product")
     */
    public function viewCart(){

        //Search 3al current_user


        return $this->render('shop/cart.html.twig',
            [
                //Traja3 cart
            ]);
    }
}