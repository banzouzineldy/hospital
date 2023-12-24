<?php

namespace App\Controller;

use Exception;
use App\Entity\ActeMedical;
use App\Form\ActeMedicalType;
use App\Repository\RdvsRepository;
use App\Repository\ExamenRepository;
use App\Form\ActeMedicalMiseAjourType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActeMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ActeMedicalController extends AbstractController
{
    #[Route('/acte/medicale', name: 'app_acte_medical')]


    public function index(Security $security,Request $request,ActeMedicalRepository $acteMedicalRepository ,EntityManagerInterface $entityManager,ExamenRepository $examenRepository): Response
    {    
      
        $user=$security->getUser();
        $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       } 
       if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
        return new RedirectResponse($this->generateUrl('app_login'));
    }
     else {
        $comptes   =     $security->getUser();
        $user     =      $comptes;
            // Récupérez le login de l'utilisateur connecté
        $login    =        $comptes->getUserIdentifier();
        $examen  = $examenRepository->findAll();
         dd($examen);
        //$rendezvouslistes = $rdvsRepository->findBy(['emailsmedecin' =>$login]);
       
        return $this->render('acte_medical/ajout.html.twig', [
               'user'     =>       $user  
             
         ]);
      
      }
      
  
        }

        // formulaire de l ajout de l acteMedical

        #[Route('/ajout/acte', name: 'app_acte_add')]
        public function ajout(EntityManagerInterface $entityManager,Request $request): Response
                  
            {  
                  if  (!empty($request->request->all()) && $request->request->all()['libelle'] && $request->request->all()['patient'] ) {
                    
                    $acte=$entityManager->getRepository(ActeMedical::class)->findOneBy([
                        "libelle" =>$request->request->all()['libelle'],
                      
                         ]); 
                         if ($acte == null){ 
                            $acte=new ActeMedical();
    
                             $acte->setLibelle($request->request->all()['libelle']);
                              $acte->setPatient($request->request->all()['patient']);
                              $acte->setExamen($request->request->all()['examen']);
                               $entityManager->persist($acte);
                               $entityManager->flush();
                             }else{
                            return $this->json([
                                'code' =>1,
                                'message' => 'cet examen  existe deja'
                            ]);
                        }
                
                        return $this->json([
                            'code' => 2,
                            'message' => 'insertion effectuée'
                        ]); 
                       
                  }   
            
            }
       
      // formulaire d edit


      #[Route('/{id}/edit/acte', name: 'app_acte_edit_form',methods:['GET'])]
      public function edit_form(EntityManagerInterface $entityManager,Security $security,Request $request,ExamenRepository $examenRepository,ActeMedicalRepository $acteMedicalRepository,int $id): Response
                
          {    $user=$security->getUser();
              $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
             if (!array_intersect($user->getRoles(), $roles)) {
               throw new AccessDeniedException('Acces refuse');
             } 
             $comptes        =        $security->getUser();
             $user           =         $comptes;
             $login          =         $comptes->getUserIdentifier();
             $examen         =          $examenRepository->findAll();
             $data           =        $acteMedicalRepository->findAll(['id'=>$id]);
              return $this->render('examen/edit.html.twig', [
                'data'         =>  $data[0],
              
               'user'        => $user  
             ]);
          
          }
       

        #[Route('/acte/supprimer', name: 'app_acte_supprimer')]
        public function delete(EntityManagerInterface $entityManager,Request $request): Response
                  
            {   
                $examen=$entityManager->getRepository(ActeMedical::class)->find(
               ['id'=>$request->request->get('id')]) ;
              $entityManager->remove($examen);
              $entityManager->flush();
            
               return $this->json([
               'code'=>1,
                'message'=>'suppresion effectué'
    
                ]);
            
            }

            #[Route('/acte/liste', name: 'app_acteMedical_liste_acte')]
            public function liste(Security $security,ActeMedicalRepository $acteMedicalRepository): Response
                      
                {   if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
    
                    return new RedirectResponse($this->generateUrl('app_login'));
                   
                   }else {
                    $user=$security->getUser();
                   $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
                  if (!array_intersect($user->getRoles(), $roles)) {
                    throw new AccessDeniedException('Acces refuse');
                  } 
    
                    $actes=$acteMedicalRepository->findAll();
                 
    
                  return $this->render('acte_medical/index.html.twig', [
                    'actes'=>$actes,
                    'user'=>$user
                ]);
                 
            }

        }



}
