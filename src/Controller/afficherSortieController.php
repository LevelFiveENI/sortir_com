<?php


namespace App\Controller;


use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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

        // a creer si pas de sorti affichage d'un tableau vide


        // recuperation des sorties
       // $sorti = $em->getRepository('App:Sortie')->findAll();


        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();

        // variable avec la date du jour
        $dateJ = date('y-m-d');

        $sorti = $em->getRepository('App:Sortie')->sortieByAll($dateJ);


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

        // on recup les infos recherche date deb
        $infoDateDeb = $request->get("dateMini");

        // on recup les infos recherche date fin
        $infoDateFin = $request->get("dateMaxi");


        // requete avec le site
        if ($infoSearch != null){
            // recup de la liste de toute les sorties en fonction du site + recherche
            $sorti = $em->getRepository('App:Sortie')->sortieBySearch($infoSite,$infoSearch, $infoDateDeb,$infoDateFin);
        }
        else{
            // on recup la liste de toute les sorties en fonction du site
            $sorti = $em->getRepository('App:Sortie')->sortieBySite($infoSite,$infoDateDeb,$infoDateFin);
        }


        return $this->render('sortie/afficherSortie.html.twig', [ 'allSortie' => $sorti ,'allSite' => $site]);
    }















}