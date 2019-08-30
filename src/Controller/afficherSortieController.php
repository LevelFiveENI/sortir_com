<?php


namespace App\Controller;




use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class afficherSortieController
 * @package App\Controller
 * @Route("/afficher", name="Sortie_afficher", methods={"GET", "POST"})
 */
class afficherSortieController extends Controller
{

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/afficSortie", name="affich_affich", methods={"GET", "POST"})
     */
    public function afficherSortie(EntityManagerInterface $em, Request $request){

        // recuperation des sorties
        $sorti = $em->getRepository('App:Sortie')->findAll();

        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();





        return $this->render('sortie/afficherSortie.html.twig', ['allSortie' => $sorti, 'allSite' => $site]);
    }



}