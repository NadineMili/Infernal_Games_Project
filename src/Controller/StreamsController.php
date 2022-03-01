<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Entity\StreamCategory;
use App\Entity\StreamComment;
use App\Entity\StreamRating;
use App\Entity\User;
use App\Form\StreamType;
use App\Repository\AdRepository;
use App\Repository\GameRepository;
use App\Repository\StreamDataRepository;
use App\Repository\StreamRatingRepository;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class StreamsController extends AbstractController
    /**
     * @Route("/streams")
     */
{
    /**
     * @Route("/", name="streams")
     */
    public function index(StreamRepository $srep, AdRepository $adRepository, GameRepository $gameRepository): Response
    {
        $game= $gameRepository->findHighestRated();
        $streams= $srep->findByState();
        $categories= $this->getDoctrine()->getRepository(StreamCategory::class)->findAll();
        $ratings= $this->getDoctrine()->getRepository(StreamRating::class)->findAll();
        return $this->render('streams/index.html.twig', [
            'controller_name' => 'StreamsController',
            'streams'=>$streams,
            'categories'=>$categories,
            'ratings'=>$ratings,
            'ads'=> $adRepository->findBy(['etat'=>true]),
            'game'=>$game[0][0]
        ]);
    }

    /**
     * @Route("/streamCommentsAjax/{id}", name="streamCommentsAjax")
     */
    public function streamCommentsAjax($id, Request $req, NormalizerInterface $normalizer){

        $comments= $this->getDoctrine()->getRepository(StreamComment::class)->findBy(['stream'=>$id]);
        $jsonData=$normalizer->normalize($comments, 'json', ['groups'=>'comments:read']);
        $jsonData[0]['user']= $comments[0]->getUser()->getUsername();
        $jsonData[0]['stream']= $comments[0]->getStream()->getId();
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/streamCommentsAjax/new", name="streamCommentsAjaxNew")
     */
    public function addStreamCommentsAjax( Request $req, NormalizerInterface $normalizer){
        $em= $this->getDoctrine()->getManager();
        $comment= new StreamComment();
        dd($req);


        $comments= $this->getDoctrine()->getRepository(StreamComment::class)->findBy(['stream'=>$id]);
        $jsonData=$normalizer->normalize($comments, 'json', ['groups'=>'comments:read']);
        $jsonData[0]['user']= $comments[0]->getUser()->getUsername();
        $jsonData[0]['stream']= $comments[0]->getStream()->getId();
        return new Response(json_encode($jsonData));
    }


    /**
     * @Route("/watch/{id}", name="watch_stream")
     */
    public function watchStream($id, Request $req)
    {
        $users= $this->getDoctrine()->getRepository(User::class)->findAll();
        $stream= $this->getDoctrine()->getRepository(Stream::class)->find($id);
        return $this->render('streams/stream.html.twig', [
            'stream'=>$stream,
            'users'=>$users
        ]);
    }

    /**
     * @Route("/new", name="stream_new")
     */
    public function newStream(Request $request, EntityManagerInterface $em, StreamRepository $srep, StreamDataRepository $sdrep): Response
    {
        $stream= new Stream();
        $form= $this->createForm(StreamType::class, $stream);

        //To change later: ye5ou el ID mta3 el current user $streamData= $sdrep->find($id);
        $streamData= $sdrep->find(1);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stream->setAccessData($streamData);
            $stream->setState(1);
            $em->persist($stream);
            $em->flush();


            $em->persist($streamData);
            $em->flush();
            return $this->redirectToRoute('streams');
        }

        return $this->render('streams/new.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


}
