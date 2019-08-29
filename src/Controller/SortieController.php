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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $etat = new Etat();

        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);

        $formLieu->handleRequest($request);
        $form->handleRequest($request);

        if ($formLieu->isSubmitted() && $formLieu->isValid()){
            //dump($request->get('lieu'));
            $tabLieux = $request->get('lieu');
            $idVille = $tabLieux['nomVille'];
            $villeLieu = $em->getRepository('App:Ville')->find($idVille);
            //dump($villeLieu);
            //exit();
            $lieu->setNomVille($villeLieu);
            $em->persist($lieu);
            $em->flush();
            dump($lieu);
            exit();
            return $this->redirectToRoute('sortie_new', ['lieu', $lieu]);

        }
        if($request->attributes->get('lieu') != null){
            $lieuChoisi = $request->attributes->get('lieu');
            dump($lieuChoisi);
            exit();
        }




        if ($form->isSubmitted() && $form->isValid()) {
            //Si le formulaire est ok, on va crée des objets Ville et Lieu associés à la sortie
            //$ville = new Ville();
            $etat = $em->getRepository('App:Etat')->find(1);
            $sortie->setLieu($lieu);
            $etat->addSortie($sortie);
            $sortie->setEtat($etat);

            //On récupère les infos nécessaires
            $tabInfos = $request->get('sortie');
            //$nomLieu = $tabInfos['lieu'];
            //$adresseLieu = $tabInfos['rue'];
            //$nomVille = $tabInfos['ville'];
            //$codeVille = $tabInfos['codePostal'];


            //On envoi en BDD
            $em->persist($sortie);
            $em->persist($lieu);
            //$em->persist($ville);
            $em->flush();
            //dump("Ok !");
            //exit();

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
}
