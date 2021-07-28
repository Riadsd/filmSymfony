<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\FilmType;
use App\Form\ImpressionType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class FilmController extends AbstractController
{
    /**
     * @Route("/film", name="film")
     */
    public function index(FilmRepository $repo): Response
    {

        $films = $repo->findAll();

        return $this->render('film/index.html.twig', [
            'film' => $films
        ]);
    }

    /**
     *
     *
     * @Route("/film/{id}", name="show_film", requirements={"id"="\d+"})
     * @Route("", name="edit_impression")
     */
    public function show(Impression $impression = null, Film $film, Request $req, ObjectManager $manager, UserInterface $user) : Response
    {

        $modeEdition = true;

        if(!$impression){

            $impression = new Impression();
            $modeEdition = false;
        }

        if($user != $impression->getUser() && $modeEdition){

            return $this->redirectToRoute('film');
        }



        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($req);


        if($form->isSubmitted() && $form->isValid()){

            if(!$modeEdition){

                $impression->setUser($user);
            }

            $impression->setCreatedAt(new \DateTime());
            $impression->setFilm($film);
            $manager->persist($impression);
            $manager->flush();

        }

        return $this->render("film/show.html.twig", [
            'formImpression'=> $form->createView(),
            'film' => $film
        ]);

    }

    /**
     * @Route("/film/new", name="film_new")
     * @Route("/film/edit/{id}", name="film_edit")
     *
     */
    public function create(Film $film = null ,Request $req, ObjectManager $manager, UserInterface $user)
    {

        $modeEdition = true;

        if(!$film){

            $film = new Film();
            $modeEdition = false;
        }

        if($user != $film->getUser() && $modeEdition){

            return $this->redirectToRoute('film');
        }


            $form = $this->createForm(FilmType::class, $film);

            $form->handleRequest($req);

            if($form->isSubmitted() && $form->isValid()){

                if(!$modeEdition){

                    $film->setUser($user);
                }

                $manager->persist($film);
                $manager->flush();



                return $this->redirectToRoute("show_film",[
                    "id" => $film->getId()
                ]);
            }


            return $this->render('film/new.html.twig',[
                'formFilm'=> $form->createView()
            ]);

    }

    /**
     *
     * @Route("/film/delete/{id}", name="film_delete")
     *
     */
    public function delete(Film $film, ObjectManager $manager)
    {


        $manager->remove($film);
        $manager->flush();

        return $this->redirectToRoute("film");
    }
}
