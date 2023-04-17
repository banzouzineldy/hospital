<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HopitalhomeController extends AbstractController
{
    #[Route('/', name: 'app_hopitalhome')]
    public function index(): Response
    {
        return $this->render('hopitalhome/index.html.twig', [

            
        ]);
    }
}
