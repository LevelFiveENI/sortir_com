<?php


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();

        // variable avec la date du jour
        $dateJ = date('y-m-d');
        $sorti = $em->getRepository('App:Sortie')->sortieByAll($dateJ);


/*        foreach ($sorti as $s){
            $p = $s -> getParticipant();
                foreach ($p as $tabParticipant){
                    dump($p);
            }
        }*/


        return $this->render('sortie/afficherSortie.html.twig', ['allSortie' => $sorti, 'allSite' => $site]);
    }


    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/afficSortieTriSite", name="affich_affich_site", methods={"POST"})
     */
    public function afficherBySite (EntityManagerInterface $em, Request $request){

        $response = new Response();


        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();

        // on recupere les infos du site
        $infoSite = $request->get("selectSite");
     //   $cookieSi = new Cookie('site', $infoSite );
        //$response->headers->setCookie($cookieSi);

        //on recup les infos de le la recherche
        $infoSearch = $request->get("infoSearch");
    //    $cookieSe = new Cookie('seek', $infoSearch );
        //$response->headers->setCookie($cookieSe);

        // on recup les infos recherche date deb
        $infoDateDeb = $request->get("dateMini");
     //   $cookieDd = new Cookie('dateD', $infoDateDeb );
        //$response->headers->setCookie($cookieDd);

        // on recup les infos recherche date fin
        $infoDateFin = $request->get("dateMaxi");
      //  $cookieDf = new Cookie('dateF', $infoDateFin );
        //$response->headers->setCookie($cookieDf);


        // on envoie les cookies
    //    $response->send();


        // test de recup des cookies
      //  $cinfoSite = $request->cookies->get('site');


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


    /**
     * @Route("/inscription", name="affich_inscription", methods={"GET", "POST"})
     */
public function ajaxInscription(Request $request, EntityManagerInterface $em){

    // on recupère l'utilisateur connecté
    $userCo = $em->getRepository('App:User')->find($this->getUser());

    //on récupère l'id de la sortie correspondant
    $idSortie = $request->get('sortieId');

    // on recup la sortie en cours
    $sortiEC = $em->getRepository('App:Sortie')->find($idSortie);


    //on set l'user dans la sortie
    $sortiEC ->addParticipant($userCo);
    $em->persist($sortiEC);
    $em->flush();

 return new \Symfony\Component\HttpFoundation\Response($idSortie);

}

    /**
     * @Route("/desinscription", name="affich_desinscription", methods={"GET", "POST"})
     */
    public function ajaxDesinscription(Request $request, EntityManagerInterface $em){

        // on recupère l'utilisateur connecté
        $userCo = $em->getRepository('App:User')->find($this->getUser());

        //on récupère l'id de la sortie correspondant
        $idSortie = $request->get('sortieId');

        // on recup la sortie en cours
        $sortiEC = $em->getRepository('App:Sortie')->find($idSortie);


        //on set l'user dans la sortie
        $sortiEC ->removeParticipant($userCo);
        $em->persist($sortiEC);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response($idSortie);

    }



    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/afficSortieCategorie", name="affich_categorie", methods={"GET", "POST"})
     */
    public function afficherSortieByCategorie(EntityManagerInterface $em, Request $request){


        // recuperation des sites
        $site = $em->getRepository('App:Site')->findAll();

        // variable avec la date du jour
        $dateJ = date('y-m-d');

       // $sorti = $em->getRepository('App:Sortie')->sortieByAll($dateJ);



        return $this->render('sortie/afficherSortie.html.twig', ['allSortie' => $sorti, 'allSite' => $site]);
    }

}