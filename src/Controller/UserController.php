<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user")
     */
    public function register(Request $req, ObjectManager $manager, UserPasswordHasherInterface $hasher): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("login");

        }

        return $this->render('user/index.html.twig', [
            'formUser' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/login" , name="login")
     */
    public function login(){

        return $this->render('user/login.html.twig');

    }

    /**
     *
     * @Route("/logout", name="logout")
     */
    public function logout(){



    }
}
