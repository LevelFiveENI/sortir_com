<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accueil", name="accueil",methods={"GET"})
 */
class AccueilController extends Controller
{
    /**
     * @Route("/", name="accueil", methods={"GET"})
     */
    public function afficher(Request $request, EntityManagerInterface $em)
    {
        $listeSorties = $em->getRepository('App:Sortie')->findByDateRecent();
//        dump($listeSorties);
//        exit();

        return $this->render('accueil.html.twig', ['listeSorties'=> $listeSorties]);
    }

}

