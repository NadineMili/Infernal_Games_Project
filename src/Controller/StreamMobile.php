<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Entity\StreamCategory;
use App\Entity\StreamRating;
use App\Entity\User;
use App\Repository\StreamCategoryRepository;
use App\Repository\StreamRatingRepository;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

class StreamMobile extends AbstractController
    /**
     * @Route("/streamsMobile")
     */
{

    /**
     * @Route("/getStreams", name="getStreams")
     */
    public function getAllStreams( Request $req, StreamRepository $streamRepository, NormalizerInterface $normalizer){
        $streams= $streamRepository->findAll();
        $jsonData=$normalizer->normalize($streams, 'json', ['groups'=>'streams:read']);
        $i=0;
        foreach ($streams as $stream){
            $jsonData[$i]['category']=$normalizer->normalize($stream->getCategory(), 'json', ['groups'=>'streamCategory:read']);
            $jsonData[$i]['rating']=$normalizer->normalize($stream->getRating(), 'json', ['groups'=>'streamRating:read']);
            $jsonData[$i]['accessData']=$normalizer->normalize($stream->getAccessData(), 'json', ['groups'=>'streamData:read']);
            $jsonData[$i++]['accessData']['streamer']=$normalizer->normalize($stream->getAccessData()->getStreamer(), 'json', ['groups'=>'users:read']);
        }

        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/getActiveStreams", name="getActiveStreams")
     */
    public function getAllActiveStreams( Request $req, StreamRepository $streamRepository, NormalizerInterface $normalizer){
        $streams= $streamRepository->findByState();
        $jsonData=$normalizer->normalize($streams, 'json', ['groups'=>'streams:read']);
        $i=0;
        foreach ($streams as $stream){
            $jsonData[$i]['category']=$normalizer->normalize($stream->getCategory(), 'json', ['groups'=>'streamCategory:read']);
            $jsonData[$i]['rating']=$normalizer->normalize($stream->getRating(), 'json', ['groups'=>'streamRating:read']);
            $jsonData[$i]['accessData']=$normalizer->normalize($stream->getAccessData(), 'json', ['groups'=>'streamData:read']);
            $jsonData[$i++]['accessData']['streamer']=$normalizer->normalize($stream->getAccessData()->getStreamer(), 'json', ['groups'=>'users:read']);
        }

        return new Response(json_encode($jsonData));
    }


    /**
     * @Route("/watchStream/{id}", name="watchStreamMobile")
     */
    public function watchStreamMobile( $id, Request $req, StreamRepository $streamRepository, NormalizerInterface $normalizer){
        $stream= $streamRepository->find($id);
        $jsonData=$normalizer->normalize($stream, 'json', ['groups'=>'streams:read']);
        $jsonData['category']=$normalizer->normalize($stream->getCategory(), 'json', ['groups'=>'streamCategory:read']);
        $jsonData['rating']=$normalizer->normalize($stream->getRating(), 'json', ['groups'=>'streamRating:read']);
        $jsonData['accessData']=$normalizer->normalize($stream->getAccessData(), 'json', ['groups'=>'streamData:read']);

        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/watch/{id}", name="watchMobile")
     */
    public function watchStream($id, Request $req){


        $stream= $this->getDoctrine()->getRepository(Stream::class)->find($id);
        $user= $this->getDoctrine()->getRepository(User::class)->find(9);
        return $this->render('streams/streamMobile.html.twig', [
            'stream'=>$stream,
            "currentUser"=> $user
        ]);

    }

    //Stream Category
    /**
     * @Route("/getAllStreamCategory", name="getAllStreamCategory")
     */
    public function getAllStreamCategory(StreamCategoryRepository $streamCategoryRepository, NormalizerInterface $normalizer){
        $categories= $streamCategoryRepository->findAll();
        $jsonData= $normalizer->normalize($categories, 'json', ['groups'=>'streamCategory:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/addStreamCategory", name="addStreamCategory")
     */
    public function addStreamCategory(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request){
        $category= new StreamCategory();
        $category->setLabel($request->get("label"));

        $em->persist($category);
        $em->flush();
        $jsonData= $normalizer->normalize($category, 'json', ['groups'=>'streamCategory:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/editStreamCategory", name="editStreamCategory")
     */
    public function editStreamCategory(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request, StreamCategoryRepository $streamCategoryRepository){

        $category= $streamCategoryRepository->find($request->get("id"));
        $category->setLabel($request->get("label"));

        $em->persist($category);
        $em->flush();
        $jsonData= $normalizer->normalize($category, 'json', ['groups'=>'streamCategory:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/deleteStreamCategory", name="deleteStreamCategory")
     */
    public function deleteStreamCategory(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request, StreamCategoryRepository $streamCategoryRepository){

        $category= $streamCategoryRepository->find($request->get("id"));

        $em->remove($category);
        $em->flush();
        return new Response();
    }

    //Stream Rating
    /**
     * @Route("/getAllStreamRating", name="getAllStreamRating")
     */
    public function getAllStreamRating(StreamRatingRepository $ratingRepository, NormalizerInterface $normalizer){
        $ratings= $ratingRepository->findAll();
        $jsonData= $normalizer->normalize($ratings, 'json', ['groups'=>'streamRating:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/addStreamRating", name="addStreamRating")
     */
    public function addStreamRating(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request){
        $rating= new StreamRating();
        $rating->setLabel($request->get("label"));

        $em->persist($rating);
        $em->flush();
        $jsonData= $normalizer->normalize($rating, 'json', ['groups'=>'streamRating:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/editStreamRating", name="editStreamRating")
     */
    public function editStreamRating(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request, StreamRatingRepository $ratingRepository){

        $rating= $ratingRepository->find($request->get("id"));
        $rating->setLabel($request->get("label"));

        $em->persist($rating);
        $em->flush();
        $jsonData= $normalizer->normalize($rating, 'json', ['groups'=>'streamRating:read']);
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/deleteStreamRating", name="deleteStreamRating")
     */
    public function deleteStreamRating(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request, StreamRatingRepository $ratingRepository){

        $rating= $ratingRepository->find($request->get("id"));

        $em->remove($rating);
        $em->flush();
        return new Response();
    }
}