<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardMedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_dashboard_medecin')]
    public function index(Security $security): Response

    { 
        $this->denyAccessUnlessGranted('ROLE_MEDECIN');
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }else   
            $comptes = $security->getUser();
            // Récupérez le login de l'utilisateur connecté
            $login = $comptes->getUserIdentifier();

        return $this->render('dashboard_medecin/index.html.twig', [
            
        ]);
    }
}
