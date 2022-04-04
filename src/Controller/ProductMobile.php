<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProductRepository;
use App\Entity\Product;

class ProductMobile extends AbstractController
 /**
     * @Route("/mobile/product", name="mobile_product")
     */
{
    /**
     * @Route("/", name="mobile_product")
     */
    public function index(): Response
    {
        return $this->render('mobile_product/index.html.twig', [
            'controller_name' => 'MobileProductController',
        ]);
    }

    /**
     * @Route("/all", name="getAllProducts")
     */
    public function allProducts(NormalizerInterface $normalizer)
    {
        $products = $this->getDoctrine()->getManager()->getRepository(  Product::class)->findALL();
        $jsonData= $normalizer->normalize($products, 'json', ['groups'=>'products:read']);
        $i=0;
        foreach ($products as $product)
            $jsonData[$i++]['category']= $normalizer->normalize($product->getCategory()->getLabel() , 'json', ['groups'=>'productsCat:read']);
        return new JsonResponse ($jsonData);
    }
      /**
     * @Route("/ajout", name="add_new")
     */

    public function ajouterReclamationAction (Request $request, NormalizerInterface $normalizer){
   $product = new Product();
   $name = $request->query->get("name");
   $description = $request->query->get("description");
   $price = $request->query->get("price");
   $brand = $request->query->get("brand");
   $quantity = $request->query->get("quantity");
   $picture = $request->query->get("picture");
        $category = $request->query->get("category");
        $product->setCategory($this->getDoctrine()->getRepository(Category::class)->findOneBy(["label"=>$category]));

   $em = $this->getDoctrine()->getManager ();
    $product->setName ($name);
   $product->setDescription($description);
   $product->setPrice($price);
   $product->setBrand( $brand);
   $product->setQuantity( $quantity);
   $product->setPicture($picture);
   $date= new \DateTime('now');
   $product->setDate($date);
    $em->persist ($product);
   $em->flush();

        $jsonData= $normalizer->normalize($product, 'json', ['groups'=>'products:read']);
        $jsonData['category']= $normalizer->normalize($product->getCategory()->getLabel(), 'json', ['groups'=>'productsCat:read']);

        return new JsonResponse ($jsonData);

}


 /**
     * @Route("/delete", name="delete_one")
     */

public function deleteProductAction (Request $request) {
    $id = $request->get("id");
    $em = $this->getDoctrine()->getManager ();
    $product = $em->getRepository(  Product::class)->find($id);
    if($product !=null ) {
       $em->remove ($product);
        $em->flush();
       $serializer =new Serializer ([new ObjectNormalizer ()]);
       $formatted = $serializer->normalize( "Produit a ete supprimée avec success.");
        return new JsonResponse ($formatted);
    }
    return new JsonResponse("id produit invalide.");


}
 /**
     * @Route("/update", name="modifier_une")
     * Method("PUT")
     */

public function modifierProductAction(Request $request, NormalizerInterface $normalizer) {
    $em = $this->getDoctrine()->getManager();
    $product = $this->getDoctrine()->getManager ()
                  ->getRepository(  Product::class)
                  ->find($request->get("id"));
    $product->setName($request->get("name"));
    $product->setDescription($request->get("description"));
    $product->setPrice($request->get("price"));
    $date= new \DateTime('now');
    $product->setDate($date);
    $category = $request->query->get("category");
    $product->setCategory($this->getDoctrine()->getRepository(Category::class)->findOneBy(["label"=>$category]));
    $em->persist ($product);
    $em->flush();
    $jsonData= $normalizer->normalize($product, 'json', ['groups'=>'products:read']);
    $jsonData['category']= $normalizer->normalize($product->getCategory()->getLabel(), 'json', ['groups'=>'productsCat:read']);
    return new JsonResponse("Produit a ete modifiee avec success.");
}

/**
     * @Route("/detail", name="detail_one")
     */


public function detailProductAction(Request $request, NormalizerInterface $normalizer) {
   $id = $request->get("id");
   $em = $this->getDoctrine()->getManager();
   $product = $this->getDoctrine()->getManager ()->getRepository(  Product::class)->find($id);

    $jsonData= $normalizer->normalize($product, 'json', ['groups'=>'products:read']);
    $jsonData['category']= $normalizer->normalize($product->getCategory(), 'json', ['groups'=>'productsCat:read']);
   return new JsonResponse ($jsonData);
}
}
