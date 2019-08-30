<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//Valentin

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
        $sortie = new Sortie();
        $lieu = new Lieu();

        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);

        $form->handleRequest($request);
        $formLieu->handleRequest($request);

        //Création d'une sortie
        if ($form->isSubmitted() && $form->isValid()) {

            $etatSortie = new Etat();
            $etatSortie = $em->getRepository('App:Etat')->find(1);

            $idLieu = $request->get('choixLieu');
            //$nomLieu = $tabLieux['nomLieu'];
            $lieuChoisi = $em->getRepository('App:Lieu')->find($idLieu);
            $sortie->setLieu($lieuChoisi);

            $etatSortie->addSortie($sortie);
            $sortie->setEtat($etatSortie);

            //On envoi en BDD
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('sortie_index');
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

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
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
        $idVille = $request->get('villeid');
        $ville = $em->getRepository('App:Ville')->find($idVille);

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



}
