<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Newsletter;
use App\Entity\Subscription;
use App\Form\NewsletterType;
use App\Repository\AdminRepository;
use App\Repository\NewsletterRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
    public function new(Request $request,MailerInterface $mailer, EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository, UserRepository $userRepository): Response{
        $newsletter= new Newsletter();
        $form=$this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $images= [$form['imageF']->getData(), $form['imageS']->getData(), $form['imageT']->getData()];

            $newsImagesNames= [$newsletter->getTitleIntro().$newsletter->getTitleF().'.'.$images[0]->guessExtension(),
                $newsletter->getTitleIntro().$newsletter->getTitleS().'.'.$images[1]->guessExtension(),
                $newsletter->getTitleIntro().$newsletter->getTitleT().'.'.$images[2]->guessExtension()];

            for ($i=0; $i<3; $i++){
                $images[$i]->move(
                    $this->getParameter('NewslettersPictures'),
                    preg_replace('/\s+/','',$newsImagesNames[$i])
                );
            }

            $newsletter->setImageF(preg_replace('/\s+/','',$newsImagesNames[0]));
            $newsletter->setImageS(preg_replace('/\s+/','',$newsImagesNames[1]));
            $newsletter->setImageT(preg_replace('/\s+/','',$newsImagesNames[2]));


            // To change later
            $currentUser= $this->getUser();
            $newsletter->setAuthor( $userRepository->find($currentUser->getId()) );

            // Get local date
            $date= new \DateTime('now');
            $newsletter->setDate($date);

            $entityManager->persist($newsletter);
            $entityManager->flush();

            if($newsletter->getSent()){
                $sub= $subscriptionRepository->findBy([
                    'status'=>1
                ]);
                for($i=0;$i<count($sub);$i++){
                    $rec= $sub[$i]->getUser()->getEmail();
                    $this->emailNewsLetter($mailer, $newsletter,$rec);
                }
            }

            return $this->redirectToRoute('admin_newsletter');
        }
        return $this->render('admin_newsletter/new.html.twig',[
            'newsletter'=> $newsletter,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/read/{id}", name="admin_newsletter_read", methods={"GET"})
     */
    public function read($id, NewsletterRepository $newsletterRepository){
        return $this->render('admin_newsletter/read.html.twig', [
            'newsletter'=>  $newsletterRepository->find($id)
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

            $images= [$form['imageF']->getData(), $form['imageS']->getData(), $form['imageT']->getData()];
            //dd($images);
            $newsImagesNames= [$newsletter->getTitleIntro().$newsletter->getTitleF().'.'.$images[0]->guessExtension(),
                $newsletter->getTitleIntro().$newsletter->getTitleS().'.'.$images[1]->guessExtension(),
                $newsletter->getTitleIntro().$newsletter->getTitleT().'.'.$images[2]->guessExtension()];


            for ($i=0; $i<3; $i++){
                $images[$i]->move(
                    $this->getParameter('NewslettersPictures'),
                    preg_replace('/\s+/','',$newsImagesNames[$i])
                );
            }


            $newsletter->setImageF(preg_replace('/\s+/','',$newsImagesNames[0]));
            $newsletter->setImageS(preg_replace('/\s+/','',$newsImagesNames[1]));
            $newsletter->setImageT(preg_replace('/\s+/','',$newsImagesNames[2]));

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
}
