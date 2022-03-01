<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Entity\StreamCategory;
use App\Entity\StreamRating;
use App\Form\StreamType;
use App\Repository\AdRepository;
use App\Repository\StreamDataRepository;
use App\Repository\StreamRatingRepository;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StreamsController extends AbstractController
    /**
     * @Route("/streams")
     */
{
    /**
     * @Route("/", name="streams")
     */
    public function index(StreamRepository $srep, AdRepository $adRepository): Response
    {
        $streams= $srep->findByState();
        $categories= $this->getDoctrine()->getRepository(StreamCategory::class)->findAll();
        $ratings= $this->getDoctrine()->getRepository(StreamRating::class)->findAll();
        return $this->render('streams/index.html.twig', [
            'controller_name' => 'StreamsController',
            'streams'=>$streams,
            'categories'=>$categories,
            'ratings'=>$ratings,
            'ads'=> $adRepository->findBy(['etat'=>true])
        ]);
    }

    /**
     * @Route("/watch/{id}", name="watch_stream")
     */
    public function watchStream($id)
    {
        $stream= $this->getDoctrine()->getRepository(Stream::class)->findById($id);
        return $this->render('streams/stream.html.twig', [
            'stream'=>$stream[0]
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
