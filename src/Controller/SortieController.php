<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Valentin *********************************************

/**
 * @Route("/sortie")
 */
class SortieController extends Controller
{
    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
//        dump($user);
//        exit();
        $sortie = new Sortie();
        $lieu = new Lieu();
        //Ici on set la date pour l'affichage dans le formulaire
        $dateJour = new \DateTime('now');
        $dateDuJourPlus = new \DateTime('now + 2 day');
        $sortie->setDateHeureDebut($dateJour);
        $sortie->setDateLimiteInscription($dateDuJourPlus);

        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);

        $form->handleRequest($request);
        $formLieu->handleRequest($request);

        //Création d'une sortie
        if ($form->isSubmitted() && $form->isValid()) {
            $etatSortie = new Etat();
            //On gère l'état de la sortie selon le bouton choisi
            if($form->get('Enregistrer')->isClicked()) {
                $this->addFlash("successCreateSortie","Votre sortie est enregistrée. Publiez-la quand vous voulez !");
                $etatSortie = $em->getRepository('App:Etat')->find(1);
            }
            if($form->get('Publier')->isClicked()) {
                $this->addFlash("successCreateSortie","Votre sortie est publiée !");
                $etatSortie = $em->getRepository('App:Etat')->find(2);
            }
//            if($form->get('Annuler')->isClicked()){
//                return $this->redirectToRoute('sortie_index');
//            }

            //On ajoute le lieu choisi a la sortie
            $idLieu = $request->get('choixLieu');
            $lieuChoisi = $em->getRepository('App:Lieu')->find($idLieu);
            $sortie->setLieu($lieuChoisi);

            $etatSortie->addSortie($sortie);

            $sortie->setEtat($etatSortie);

            //On relie la sortie à l'utilisateur
            $sortie->setOrganisateur($user);

            //On envoi en BDD
            $em->persist($sortie);
            //$em->persist($user);
            $em->flush();



            return $this->redirectToRoute("Sortie_afficheraffich_affich");
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'lieu'=>$lieu,
            'form' => $form->createView(),
            'formLieu' => $formLieu->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        $tabParticipants = $sortie->getParticipant()->toArray();

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie, 'tabParticipants' => $tabParticipants,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $em): Response
    {
        $lieu = $sortie->getLieu();
        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);
        $formLieu->handleRequest($request);

        //Si le formulaire est validé
        if ($form->isSubmitted() && $form->isValid()) {
            $etatSortie = new Etat();
            //On gère l'état de la sortie selon le bouton choisi
            if($form->get('Enregistrer')->isClicked()) {
                $etatSortie = $em->getRepository('App:Etat')->find(1);
                $sortie->setEtat($etatSortie);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("successCreateSortie","Votre sortie est enregistrée. Publiez-la quand vous voulez !");
            }
            if($form->get('Publier')->isClicked()) {
                $etatSortie = $em->getRepository('App:Etat')->find(2);
                $sortie->setEtat($etatSortie);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("successCreateSortie","Votre sortie est publiée !");
            }

            if($form->get('Supprimer')->isClicked()) {
                $etatSortie = $em->getRepository('App:Etat')->find(6);
                $sortie->setEtat($etatSortie);
                $sortie->setEtat($etatSortie);
                $em->persist($sortie);
                $em->flush();
                $this->addFlash("successCreateSortie","Votre sortie à été supprimée !");
            }
            return $this->redirectToRoute("Sortie_afficheraffich_affich");
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'lieu'=>$lieu,
            'form' => $form->createView(),
            'formLieu' => $formLieu->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @Route("/ajaxLieu", name="ajax_lieu", methods={"GET", "POST"})
     */
    public function ajaxLieu(Request $request, EntityManagerInterface $em){
        //Fonction AJAX pour relier les select du formaulaire
        $idVille = $request->get('villeid');
        $ville = $em->getRepository('App:Ville')->find($idVille);
        //On récupère l'id de la ville et on cherche la liste des lieux de la ville
        //Puis on envoi les données en JSON
        if ($idVille){
            $villeLieu = $em->getRepository('App:Ville')->find($idVille);
            $arrayLieux = array();
            $villeLieu->getLieux();
            foreach($villeLieu->getLieux() as $lieu){
                $nomLieu = $lieu->getNom();
                $idLieu = $lieu->getId();
                $arrayLieux[$idLieu] = $nomLieu;
            }
        }
        $lieuxJson = json_encode($arrayLieux);
        return new Response($lieuxJson);
    }


    /**
     * @Route("sortieCategorie/{id}", name="sortie_categorie", methods={"GET"})
     */
    public function affichageCategorie(Request $request, EntityManagerInterface $em): Response
    {
        $site = $em->getRepository('App:Site')->findAll();
        $id = $request->get('id');
        // recuperer la liste des sorties
        $result = $em->getRepository('App:Sortie')->sortieByCategorie($id);

        return $this->render('sortie/afficherSortie.html.twig', ['allSortie' => $result, 'allSite' => $site]);
     
    }


}
