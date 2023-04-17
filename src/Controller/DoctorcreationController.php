<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorcreationController extends AbstractController
{
    #[Route('/doctorcreation', name: 'app_doctorcreation')]
    public function index(): Response
    {
        return $this->render('doctorcreation/index.html.twig', [
            'controller_name' => 'DoctorcreationController',
        ]);
    }
}
