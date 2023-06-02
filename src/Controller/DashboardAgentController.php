<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DashboardAgentController extends AbstractController
{
    #[Route('/agent', name: 'app_dashboard_agent')]
    public function index(Security $security): Response

    { 
        
        $this->denyAccessUnlessGranted('ROLE_AGENT');
       // $this->denyAccessUnlessGranted('ROLE_MEDECIN');
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }else   
            $comptes = $security->getUser();
            // Récupérez le login de l'utilisateur connecté
            $login = $comptes->getUserIdentifier(); 
        return $this->render('dashboard_agent/index.html.twig', [
            
        ]);
    }
}
