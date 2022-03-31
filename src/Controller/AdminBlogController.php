<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\AdminRepository;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/blogs")
 */
class AdminBlogController extends AbstractController
{
    /**
     * @Route("/", name="admin_blogs", methods={"GET"})
     */
    public function index(Request $request, BlogRepository $blogRepository, PaginatorInterface $paginator): Response
    {
        $donnees = $blogRepository->findAll();
        $blogs = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
             3// Nombre de résultats par page
        );

        return $this->render('admin_blogs/index.html.twig', [
            'blogs' => $blogs
        ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control w-100',
                    'placeholder' => 'Search for a blog'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, BlogRepository $repo, PaginatorInterface $paginator)
    {
        $query = $request->request->get('form')['query'];
        if($query) {
            $donnees = $repo->findArticlesByName($query);
        } else {
            $donnees = $repo->findAll();
        }

        $blogs = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('admin_blogs/index.html.twig', [
            'blogs' => $blogs
        ]);
    }

    /**
     * @Route("/new", name="admin_blogs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentUser= $this->getUser();
            $blog->setAuthor($userRepository->find( $currentUser->getId()));

            $image = $form['image']->getData();
            $newImageName= $blog->getTitle().'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('BlogsPictures'),
                $newImageName
            );
            $blog->setImage($newImageName);


            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blogs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_blogs/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_blogs_show", methods={"GET"})
     */
    public function show(Blog $blog): Response
    {
        return $this->render('admin_blogs/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_blogs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form['image']->getData();
            if ($image) {
                $newImageName= $blog->getTitle().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('BlogsPictures'),
                    $newImageName
                );
                $blog->setImage($newImageName);
            }


            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blogs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_blogs/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_blogs_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, $id, BlogRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $blog = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();
        return $this->redirectToRoute('admin_blogs', [], Response::HTTP_SEE_OTHER);

    }
}