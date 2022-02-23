<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Form\StreamType;
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
    public function index(StreamRepository $srep): Response
    {
        $streams= $srep->findByStatus();
        return $this->render('streams/index.html.twig', [
            'controller_name' => 'StreamsController',
            'streams'=>$streams
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
            $em->persist($stream);
            $em->flush();

            $streamData->setStatus(true);
            $em->persist($streamData);
            $em->flush();
            return $this->redirectToRoute('streams');
        }

        return $this->render('streams/new.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


}
