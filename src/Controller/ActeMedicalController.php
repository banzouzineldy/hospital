<?php

namespace App\Controller;

use Exception;
use App\Entity\ActeMedical;
use App\Entity\AutreActemedical;
use App\Entity\Eldy;
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


    public function index(Security $security,Request $request,ActeMedicalRepository $acteMedicalRepository ,EntityManagerInterface $entityManager,ExamenRepository $examenRepository,RdvsRepository $rdvsRepository): Response
    {    
      
        $user=$security->getUser();
        $examenfinal=[];
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
        $login    =    $comptes->getUserIdentifier();
        $examens   =$examenRepository->findBy(['utilisateur'=>$login]);
        
       // $rendez_vous     =    $rdvsRepository->findAll();
         return $this->render('acte_medical/ajout.html.twig', [
               'user'     =>   $user ,
               'examens'  =>  $examens,     
             
         ]);
      
      }
    
        }

        // formulaire de l ajout de l acteMedical

        #[Route('/ajout/acte', name: 'app_acte_add',methods:['POST'])]
        public function ajout(EntityManagerInterface $entityManager,Request $request,Security $security): Response
                  
            {     
              
                  if  (!empty($request->request->all()) && $request->request->all()['soin'] ) {
                       
                            $user=$security->getuser();
                             $acte=new ActeMedical();
                             $acte->setSoin($request->request->all()['soin']);
                               $acte->setPatient($request->request->all()['patient']);
                               $acte->setExamen($request->request->all()['examen']);
                               $acte->setUtilisateur($user->getUserIdentifier());
                               $entityManager->persist($acte);
                               $entityManager->flush();
                             }
                         return $this->json([
                            'code' => 2,
                            'message' => 'insertion effectuée'
                        ]);      
                    
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
            
             $examens        =       $examenRepository->findBy(['utilisateur'=>$login]);
             $data           =     $acteMedicalRepository->findAll(['id'=>$id]);
              return $this->render('acte_medical/edit.html.twig', [
                'data'         =>  $data[0],
               'user'        => $user ,
               'examens'      => $examens
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

            #[Route('/acte/listes', name: 'app_acteMedical_liste_acte')]
            public function liste(Security $security,ActeMedicalRepository $acteMedicalRepository): Response
                      
                {   if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
    
                    return new RedirectResponse($this->generateUrl('app_login'));
                   
                   }else {
                    $user=$security->getUser();
                   $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
                  if (!array_intersect($user->getRoles(), $roles)) {
                    throw new AccessDeniedException('Acces refuse');
                  }   
                  $login    = $user->getUserIdentifier();
                  $actes   =  $acteMedicalRepository->findBy(['utilisateur'=>$login]);
                 
                  return $this->render('acte_medical/index.html.twig', [
                    'actes'=>$actes,
                    'user'=>$user
                ]);
                 
            }

        }



        #[Route('/edit/acte/{id}', name: 'app_acte_edit',methods:['POST'])]
        public function edit(EntityManagerInterface $entityManager,Request $request,$id,ActeMedicalRepository $acteMedicalRepository,Security $security): Response
                  
            {   
                $acte=$acteMedicalRepository->find($id);

                if  (!$acte) {
                   
                    throw $this->createNotFoundException(
                        'No product found for id '.$id
                    );
                }
                $user=$security->getuser();
                $acte->setSoin($request->request->all()['soin']);
                $acte->setPatient($request->request->all()['patient']);
                $acte->setExamen($request->request->all()['examen']);
                $acte->setUtilisateur($user->getUserIdentifier());
                $entityManager->flush();
                return $this->json([
                    'code' => 2,
                    'message' => 'mise effectue'
                ]); 
                
            }



}
