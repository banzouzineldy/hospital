<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('dashboard/home.html.twig', [
            
        ]);
    }


    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('dashboard/admin.html.twig', [
            
        ]);
    }
    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('dashboard/connexion.html.twig', [
            
        ]);
    }


}
