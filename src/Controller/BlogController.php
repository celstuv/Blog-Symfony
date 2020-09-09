<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository; // interroger notre repository ArticleRepository
use App\Repository\CommentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Hello World',
        ]);
    }
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'Articles',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Request $request, Article $article = null)
    { // création d'un nouvel article via un formulaire
      if(!$article){
          $article =  new Article();
      }
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request); //Formulaire analyse la requete http du formulaire

        if ($form->isSubmitted() && $form->isValid()){
          if(!$article->getId()){
              $article ->setCreatedAt(new \DateTime());
          }
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($article);
          $entityManager->flush();

           return $this->redirectToRoute('blog_show', [
             'id' => $article->getid()
              ]);
        }
        return $this->render('blog/create.html.twig', [
          'formArticle'=> $form->createView(),
          'editMode' => $article->getId() !== null //boolean pour savoir si Id existe
        ]);
      }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(ArticleRepository $repository, $id ,Request $request)
    { // je souhaite faire apparaitre les différents articles selon leur identifiant
      // ici, j'interroge mon repository et je lui demande les id de la class
      // je souhaite modifier les commentaires de mes articles
        $comment = new Comment();
        $article = $repository->find($id);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request); //Formulaire gère la request
        //Si mon form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

          $comment->setCreatedAt(new \DateTime())
                  ->setArticle($article);
          //Alors je fais appel à un manager pour exécuter ma requête
          $manager = $this->getDoctrine()->getManager();
          $manager->persist($comment);
          $manager->flush();

          return $this->redirectToRoute('blog_show', [
            'id' => $article->getid()
             ]);
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

}
