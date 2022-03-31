<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Repository\NewsletterRepository;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Game;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use function Symfony\Component\String\s;


Class NewsletterMobile extends AbstractController
    /**
     * @Route("/newslettersMobile")
     */
{

    /**
     * @Route("/getAllNewsletters")
     */
    public function getAllNewsletter(NewsletterRepository $newsletterRepository, NormalizerInterface $normalizer){
        $newsletters= $newsletterRepository->findAll();
        $jsonData= $normalizer->normalize($newsletters, 'json', ['groups'=>'newsletters:read']);
        $i=0;
        foreach ($newsletters as $newsletter){
            $jsonData[$i]['date']= $newsletter->getDate()->format("Y-m-d");
            $jsonData[$i++]['author']= $normalizer->normalize($newsletter->getAuthor(), 'json', ['groups'=>'users:read']);

        }
        return new Response(json_encode($jsonData));
    }

    /**
     * @Route("/addNewsletter")
     */
    public function addNewsletter(Request $request, MailerInterface $mailer, NormalizerInterface $normalizer, EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository){
        $newsletter= new Newsletter();
        $newsletter->setTitleIntro( $request->get("titleIntro") );
        $newsletter->setContentIntro( $request->get("contentIntro") );

        $newsletter->setTitleF( $request->get("titleF") );
        $newsletter->setContentF( $request->get("contentF") );
        $newsletter->setImageF( $request->get("imageF") );

        $newsletter->setTitleS( $request->get("titleS") );
        $newsletter->setContentS( $request->get("contentS") );
        $newsletter->setImageS( $request->get("imageS") );

        $newsletter->setTitleT( $request->get("titleT") );
        $newsletter->setContentT( $request->get("contentT") );
        $newsletter->setImageT( $request->get("imageT") );

        $date= new \DateTime('now');
        $newsletter->setDate($date);
        $newsletter->setSent( $request->get("sent") );
        $newsletter->setAuthor( $this->getDoctrine()->getRepository(User::class)->find($request->get("author")) );

        $entityManager->persist($newsletter);
        $entityManager->flush();

        if ($newsletter->getSent()){
            $sub= $subscriptionRepository->findBy([
                'status'=>1
            ]);
            for($i=0;$i<count($sub);$i++){
                $rec= $sub[$i]->getUser()->getEmail();
                $this->emailNewsLetter($mailer, $newsletter,$rec);
            }
        }

        $jsonData= $normalizer->normalize($newsletter, 'json', ['groups'=>'newsletters:read']);
        return new Response(json_encode($jsonData));
    }


    /**
     * @Route("/editNewsletter")
     */
    public function editNewsletter(Request $request, MailerInterface $mailer, NormalizerInterface $normalizer, EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository){
        $newsletter= $this->getDoctrine()->getRepository(Newsletter::class)->find($request->get("id"));
        $newsletter->setTitleIntro( $request->get("titleIntro") );
        $newsletter->setContentIntro( $request->get("contentIntro") );

        $newsletter->setTitleF( $request->get("titleF") );
        $newsletter->setContentF( $request->get("contentF") );
        $newsletter->setImageF( $request->get("imageF") );

        $newsletter->setTitleS( $request->get("titleS") );
        $newsletter->setContentS( $request->get("contentS") );
        $newsletter->setImageS( $request->get("imageS") );

        $newsletter->setTitleT( $request->get("titleT") );
        $newsletter->setContentT( $request->get("contentT") );
        $newsletter->setImageT( $request->get("imageT") );

        $date= new \DateTime('now');
        $newsletter->setDate($date);
        $newsletter->setSent( $request->get("sent") );
        $newsletter->setAuthor( $this->getDoctrine()->getRepository(User::class)->find($request->get("author")) );

        $entityManager->persist($newsletter);
        $entityManager->flush();

        if ($newsletter->getSent()){
            $sub= $subscriptionRepository->findBy([
                'status'=>1
            ]);
            for($i=0;$i<count($sub);$i++){
                $rec= $sub[$i]->getUser()->getEmail();
                $this->emailNewsLetter($mailer, $newsletter,$rec);
            }
        }

        $jsonData= $normalizer->normalize($newsletter, 'json', ['groups'=>'newsletters:read']);
        return new Response(json_encode($jsonData));
    }


    public function emailNewsLetter(MailerInterface $mailer, Newsletter $newsletter, $rec){


        //src="{{ email.image('@newsletterImages/img/infernalLogo.png') }}"
        $email = (new TemplatedEmail())
            ->from('infernalgames2022@gmail.com')
            ->to($rec)
            ->subject("Infernal Games Newsletter!")
            ->htmlTemplate('newsletter\template3.html.twig')
            ->context([ 'newsletter'=>$newsletter]);

        $mailer->send($email);
        return $this->redirectToRoute('admin_newsletter');
    }

    /**
     * @Route("/deleteNewsletter", name="deleteNewsletter")
     */
    public function deleteNewsletter(NormalizerInterface $normalizer, EntityManagerInterface $em, Request $request, NewsletterRepository $newsletterRepository){

        $newletter= $newsletterRepository->find($request->get("id"));

        $em->remove($newletter);
        $em->flush();
        return new Response();
    }
}
