<?php


namespace App\Controller;


use App\Entity\Sortie;
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


    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/afficSortieTriSite", name="affich_affich_site", methods={"POST"})
     */
    public function afficherBySite (EntityManagerInterface $em, Request $request){
        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();

        // on recupere les infos du site
        $infoSite = $request->get("selectSite");
        //on recup les infos de le la recherche
        $infoSearch = $request->get("infoSearch");

        // requete avec le site


        if ($infoSearch != null){
            // recup de la liste de toute les sorties en fonction du site + recherche
            $sorti = $em->getRepository('App:Sortie')->sortieBySearch($infoSite,$infoSearch);
        }
        else{
            // on recup la liste de toute les sorties en fonction du site
            $sorti = $em->getRepository('App:Sortie')->sortieBySite($infoSite);
        }


        return $this->render('sortie/afficherSortie.html.twig', [ 'allSortie' => $sorti ,'allSite' => $site]);
    }















}