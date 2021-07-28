<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionController extends AbstractController
{
    /**
     * @Route("/impression/delete/{id}", name="impression_delete")
     */
    public function delete( Impression $avis, ObjectManager $manager): Response
    {

        $manager->remove($avis);
        $manager->flush();



        return $this->redirectToRoute("film");
    }
}
