<?php


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/{id}", name="user_show_profile", methods={"GET"})
     * @param Request $req
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function showUserProfile(Request $req, EntityManagerInterface $em): Response
    {
        $idUser = $req->get('id');
        $user = $em->getRepository('App:User')->find($idUser);


        return $this->render('user/userShow.html.twig', [
            'user' => $user
        ]);
    }

}