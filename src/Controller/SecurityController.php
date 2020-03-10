<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            // ... persist the $product variable or any other work

            // J'encode le mode de passe envoyé par l'utilisateur et je le stocke dans une variable
            $hash = $encoder->encodePassword($user, $user->getPassword());
            // je remplace la valeur entrée par l'utilisateur pour la mettre par une valeur cryptée
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('security_login');
          }
        return $this->render('security/registration.html.twig',[
          'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
      return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function loggout()
    {
      // return $this->render('security/login.html.twig');
    }
}
