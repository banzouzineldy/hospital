<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router): Response
    {
 /*        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            // Rediriger l'utilisateur vers une route spécifique pour les administrateurs
            return new RedirectResponse($router->generate('admin_dashboard'));
        } elseif ($authorizationChecker->isGranted('ROLE_USER')) {
            // Rediriger l'utilisateur vers une route spécifique pour les utilisateurs normaux
            return new RedirectResponse($router->generate('user_dashboard'));
            // return new RedirectResponse($this->urlGenerator->generate('app_dashboard_medecin'));
        } */

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
