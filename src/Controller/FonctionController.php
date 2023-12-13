<?php

namespace App\Controller;

use App\Repository\FonctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FonctionController extends AbstractController
{
    #[Route('/fonction', name: 'app_fonction')]
    public function index(Security $security,EntityManagerInterface $entityManagerInterface,FonctionRepository $fonctionRepository): Response

    { 
        $user=$security->getUser();
        $comptes=$user;
        $roles=['ROLE_ADMIN'];
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       }
       $fonctions=$fonctionRepository->findAll();

        return $this->render('fonction/index.html.twig', [
            'fonction'=>$fonctions,
            'user'=>$comptes
            
        ]);
    }
}
