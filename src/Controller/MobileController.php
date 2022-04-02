<?php

namespace App\Controller;


use App\Entity\Commantaires;
use App\Entity\Equipes;
use App\Entity\Joueurs;
use App\Entity\Like;
use App\Entity\Matchs;
use App\Entity\Publications;
use App\Entity\Tournois;
use App\Entity\User;
use App\Repository\CommantairesRepository;
use App\Repository\EquipesRepository;
use App\Repository\JoueursRepository;
use App\Repository\LikeRepository;
use App\Repository\MatchsRepository;
use App\Repository\PublicationsRepository;
use App\Repository\TournoisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineExtensions\Query\Mysql\Date;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

class MobileController extends AbstractController
{
    /**
     * @Route("/mobile/login_mobile/{email}/{password}", name="login_mobile")
     */
    public function login_mobile($email,$password,UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $user=   $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('email' => $email, 'activation_token'=>null));

        $prd = array();
        if($user) {
            $passwordValid = $encoder->isPasswordValid($user, $password);
if($passwordValid) {
    $prd = array(
        'id' => $user->getId(),
        'name' => $user->getName(),
        'password' => $user->getPassword(),
        'email' => $user->getEmail(),
        'role' => $user->getRoles(),
        'image' => $user->getImage(),
        'lastname' => $user->getLastName()

    );

}
        }



        return new JsonResponse($prd);

    }

    /**
     * @Route("/mobile/inscrireMobile", name="inscrireMobile")
     */
    public function inscrireMobile( UserPasswordEncoderInterface $userPasswordEncoder,\Swift_Mailer $mailer,Request $request){

        $name = $request->query->get('name');
        $lastName = $request->query->get('lastName');
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $pathimage = $request->query->get('pathimage');


        $user =new User();
        $user->setName($name);
        $user->setLastName($lastName);
        $user->setEmail($email);





        $destinationfile=md5(uniqid()).".png";
        $destination=$this->getParameter('images_directory').'/products/'. $destinationfile;

        copy($pathimage, $destination);

        $user->setImage($destinationfile);

        $user->setPassword($password);
        $user->setPassword(
            $userPasswordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $user->setActivationToken(md5(uniqid()));

        $em=$this->getDoctrine()->getManager();


        try {
            $em->persist($user);
            $em->flush();

            $message=(new \Swift_Message('Activation de votre compte'))
                ->setFrom('infernalgames2022@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig',['token'=>$user->getActivationToken()]
                    ),'text/html'
                );
            $mailer->send($message);





        } catch(\Exception $ex)
        {
            die($ex);
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }

        return $this->json(array('title'=>'successful','message'=> "utilisateur ajoute avec succes"),200);


    }

    /**
     * @Route("/mobile/getAllUsers", name="getAllUsers")
     */
    public  function getAllUsers(){
        ;
        $em = $this->getDoctrine()->getManager();

        $users=   $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $jsonData=array();
        $prd=array();
        $i=0;
        foreach ($users as $user){

            $prd = array(
                'id' => $user->getId(),
                'name' => $user->getName(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles()

            );
            $jsonData[$i++] = $prd;
        }


        return new JsonResponse($jsonData);

    }
    /**
     * @Route("/mobile/getUserByEmail/{email}", name="getUserByEmail")
     */
    public function getUserByEmail($email)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('email' => $email));
        $prd = array();
        if ($user) {
            $prd = array(
                'id' => $user->getId(),
                'name' => $user->getName(),
                'lastname' => $user->getLastName(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
                'image' => $user->getImage()

            );
        }

        return new JsonResponse($prd);
    }

    /**
     * @Route("/mobile/removeUser/{email}", name="removeUser")
     */
    public function removeUser($email)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('email' => $email));

        $em->remove($user);
        $em->flush();
        return $this->json(array('title'=>'successful','message'=> "utilisateur supprimé avec succès"),200);
    }


    /**
* @Route("/mobile/updateUser", name="updateUser")
*/
    public function updateUser( UserPasswordEncoderInterface $userPasswordEncoder,\Swift_Mailer $mailer,Request $request){

        $name = $request->query->get('name');
        $lastName = $request->query->get('lastName');
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $pathimage = $request->query->get('pathimage');


        $user =$em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('email' => $email));

        $user->setName($name);
        $user->setLastName($lastName);



        $destinationfile=md5(uniqid()).".png";
        $destination=$this->getParameter('images_directory').'/products/'. $destinationfile;

        copy($pathimage, $destination);

        $user->setImage($destinationfile);

        $user->setPassword($password);
        $user->setPassword(
            $userPasswordEncoder->encodePassword(
                $user,
                $password
            )
        );



        $em=$this->getDoctrine()->getManager();


        try {
            $em->persist($user);
            $em->flush();

            $message=(new \Swift_Message('Changement de coordonéés'))
                ->setFrom('infernalgames2022@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                   "Nous vous informons que vos cordonnées ont été changés avec succès"
                );
            $mailer->send($message);



        } catch(\Exception $ex)
        {
            die($ex);
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }

        return $this->json(array('title'=>'successful','message'=> "utilisateur ajouté avec succès"),200);


    }



    /**
     * @Route("/mobile/valider/{token}", name="valider")
     */
    public function valider( $token){
        $user =$em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('activation_token' => $token));

        $prd = array();
        if($user) {
            $prd = array(
                'id' => $user->getId(),
                'name' => $user->getName(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
                'image' => $user->getImage(),
                'lastname' => $user->getLastName()

            );


            $user->setActivationToken(null);

            $em = $this->getDoctrine()->getManager();


            try {
                $em->persist($user);
                $em->flush();


            } catch (\Exception $ex) {



            }
        }
        return new JsonResponse($prd);

    }





    /**
     *@Route("/mobile/sendpdf/{mail}", name="sendpdf")
     */
    public function sendpdf(UserRepository  $repository,\Swift_Mailer $mailer,$mail)
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

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('images_directory').'/products/';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/Users.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);


        $message=(new \Swift_Message('Liste Users'))
            ->setFrom('infernalgames2022@gmail.com')
            ->setTo($mail)
            ->setBody(
                "Bonjour, Voici la liste des utilisateurs , ci-joint. Cordialement,"
            )
            ->attach(\Swift_Attachment::fromPath($pdfFilepath));
        $mailer->send($message);
        return $this->json(array('title'=>'successful','message'=> "pdf envoyé"),200);

    }


    //*******Forum************//

}