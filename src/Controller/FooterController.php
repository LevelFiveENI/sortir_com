<?php


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends Controller
{
    /**
     * @Route("/mentionslegales", name="mentionslegales", methods={"GET"})
     */
    public function afficherMentions()
    {
        return $this->render('footer/aproposde.html.twig');
    }

    /**
     * @Route("/lequipe", name="lequipe", methods={"GET"})
     */
    public function afficherLequipe()
    {
        return $this->render('footer/lequipe.html.twig');
    }

}