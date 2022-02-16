<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Newsletter;
use App\Entity\Subscription;
use App\Form\NewsletterType;
use App\Repository\AdminRepository;
use App\Repository\NewsletterRepository;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class AdminNewsletterController extends AbstractController
    /**
     * @Route("/admin/newsletter")
     */
{
    /**
     * @Route("/", name="admin_newsletter")
     */
    public function index(NewsletterRepository $newsletterRepository): Response
    {
        return $this->render('admin_newsletter/index.html.twig', [
            'newsletters' => $newsletterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_newsletter_new", methods={"GET", "POST"})
     */
    public function new(Request $request,MailerInterface $mailer, EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository, AdminRepository $adminRepository): Response{
        $newsletter= new Newsletter();
        $form=$this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // To change later
            $newsletter->setAuthor( $adminRepository->find(1) );

            // Get local date
            $date= new \DateTime('now');
            $newsletter->setDate($date);

            if($newsletter->getSent()){
                $sub= $subscriptionRepository->findBy([
                    'status'=>1
                ]);

                $entityManager->persist($newsletter);
                $entityManager->flush();
                for($i=0;$i<count($sub);$i++){
                    $rec= $sub[$i]->getUser()->getEmail();
                    $this->emailNewsLetter($mailer, $newsletter,$rec);
                }
            }
            return $this->redirectToRoute('admin_newsletter', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_newsletter/new.html.twig',[
            'newsletter'=> $newsletter,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_newsletter_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, $id, NewsletterRepository $newsletterRepository, SubscriptionRepository $subscriptionRepository): Response
    {
        $newsletter= $newsletterRepository->find($id);
        $author= $newsletter-> getAuthor();
        $form=$this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //To change later
            $newsletter-> setAuthor($author);

            // Get local date
            $date= new \DateTime('now');
            $newsletter->setDate($date);
            $entityManager->persist($newsletter);
            $entityManager->flush();
            $sub= $subscriptionRepository->findBy([
                'status'=>1
            ]);
            for($i=0;$i<count($sub);$i++){
                $rec= $sub[$i]->getUser()->getEmail();
                $this->emailNewsLetter($mailer, $newsletter,$rec);
            }
            return $this->redirectToRoute('admin_newsletter', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin_newsletter/new.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", name="admin_newsletter_delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager,$id, NewsletterRepository $newsletterRepository): Response
    {
        $newsletter= $newsletterRepository->find($id);
        $entityManager->remove($newsletter);
        $entityManager->flush();
        return $this->redirectToRoute('admin_newsletter', [], Response::HTTP_SEE_OTHER);
    }

    public function emailNewsLetter(MailerInterface $mailer, Newsletter $newsletter, $rec){

        $email = (new Email())
            ->from('infernalgames2022@gmail.com')
            ->to($rec)
            ->subject( $newsletter->getTitle())
            ->html('
                            <h1>{$newsletter->getTitle()}</h1>
                            <p>{$newsletter->getContent()}</p>
                            <footer>{$newsletter->getAuthor()}</footer>
                    ');
        $l= (MailerInterface::class);

        $mailer->send($email);
        return $this->redirectToRoute('admin_newsletter');
    }
}
