<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\GamerRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
    /**
     * @Route("/admin/users")
     */
{
    /**
     * @Route("/bruh", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route ("/profile" , name="profile")
     */
    public function profile(\Symfony\Component\Security\Core\Security $sec)
    {
        $user= $sec->getUser();
        return $this->render('gamer/user.html.twig',['user'=>$user]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @param UserRepository $repository
     * @return Response
     * @Route ({"/","/afficheuser"},name="afficheuser")
     */
    public function afficheuser(UserRepository $repository)
    {
        //$repository=$this->getDoctrine()->getRepository(Classroom::class);
        $tabuser=$repository->findAll();
        return $this->render('user/afficheuser.html.twig',[
            'tab'=>$tabuser
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route ("/delete/{id}",name="deleteUser")
     */
    public function delete($id)
    {
        $repository=$this->getDoctrine()->getRepository(User::class);
        $rep=$this->getDoctrine()->getRepository(Panier::class);

        $user=$repository->find($id);
        $panier=$rep->findOneBy(['user'=>$user]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($panier);
        $em->flush();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('afficheuser');
    }
    /**
     * @Route ("/updateuser/{id}" , name="updateuser")
     */
    public function update($id,UserRepository $repository ,Request $request)
    {
        $user=$repository->find($id);
        $form=$this->createForm(RegistrationFormType::class, $user);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheuser');
        }
        return $this->render('user/update.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route ("/testvideo", name="testvideo")
     */
    public function testvid()
    {
        return $this->render('user/livestream.html.twig');
    }

    /**
     * @Route("/admrecherchelivr1", name="admrecherchelivr1")
     */
    public function searchLivAction(Request $request,UserRepository $repository){
        $em = $this->getDoctrine()->getManager();


        $searchParameter = $request->get('User');
        if(strlen($searchParameter)==0)
            $entities = $em->getRepository(Prod::class)->findAll();
        else


            $entities = $repository->findByExpField($searchParameter);



        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entities, 'json',['ignored_attributes'=>['password','roles','image','imageFile','activation_token','reset_token']

        ]);

        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }
    /**
     *@Route("/pdfnav", name="panier_pdfnav", methods={"GET"})
     */
    public function pdfnav(UserRepository  $repository)
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $prod=$repository->findAll();




        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $html= $this->render("user/pdf.html.twig",['tour'=>$prod]);



        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("Facture.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/new", name="admin_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request,
                        UserPasswordEncoderInterface $passwordEncoder
        //                    ,GuardAuthenticatorHandler $guardHandler,
        //                    AppcAuthenticator $authenticator
    ) : Response
    {
        $user = new User();
        $form =$this->createForm(RegistrationFormType::class, $user );
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_users');

        }
        return $this->render('admin_user/new.html.twig', [
            'user' =>$user,
            'form' => $form -> createView()
        ]);
    }

}