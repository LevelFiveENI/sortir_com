<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Valentin ********************************
/**
 * @Route("/lieu")
 */
class LieuController extends Controller
{
    /**
     * @Route("/", name="lieu_index", methods={"GET"})
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lieu_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        //Ici on récupère en AJAX le formulaire pour créer un lieu
        $lieu = new Lieu();
        $newLieuNom = $request->get('newLieuNom');
        $newLieuRue = $request->get('newLieuRue');
        $newLieuVilleId = $request->get('newLieuVille');
        $newLieuLong = $request->get('newLieuLong');
        $newLieuLat = $request->get('newLieuLat');

        $villeDuLieu = $em->getRepository('App:Ville')->find($newLieuVilleId);

        //On set les attributs
        $lieu->setNom($newLieuNom);
        $lieu->setRue($newLieuRue);
        $lieu->setNomVille($villeDuLieu);
        if(isset($newLieuLat) && isset($newLieuLong)){
            $lieu->setLongitude($newLieuLong);
            $lieu->setLatitude($newLieuLat);
        }


        //On ajoute en BDD
        $em->persist($lieu);
        $em->flush();
        return new response("OK !");

    }

    /**
     * @Route("/{id}", name="lieu_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lieu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lieu $lieu): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lieu_index');
        }

        return $this->render('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lieu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lieu $lieu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lieu_index');
    }
}
