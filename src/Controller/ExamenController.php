<?php

namespace App\Controller;

use Exception;
use App\Entity\Examen;
use App\Form\ExamenType;
use App\Form\ExamenMiseAjourType;
use App\Repository\ExamenRepository;
use App\Repository\RdvsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExamenController extends AbstractController
{
    #[Route('/examen/ajout', name: 'app_examen_add')]
    public function index(Security $security, EntityManagerInterface $entityManager,ExamenRepository $examenRepository,Request $request,RdvsRepository $rdvsRepository): Response
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
        $rendez_vous     =    $rdvsRepository->findAll();
        $rendezvouslistes = $rdvsRepository->findBy(['emailsmedecin' =>$login]);
       
        return $this->render('examen/ajout.html.twig', [
            'rendezvousS'  =>   $rendezvouslistes,
               'user'     =>       $user  
             
         ]);
      
      }

       
        }

        // ajout d un examen

        #[Route('/ajout/examen', name: 'app_examen_add_examen')]
        public function ajout(EntityManagerInterface $entityManager,Request $request): Response
                  
            {  
                  if  (!empty($request->request->all()) && $request->request->all()['libelle'] && $request->request->all()['patient'] ) {
                    
                    $examen=$entityManager->getRepository(Examen::class)->findOneBy([
                        "libelle" =>$request->request->all()['libelle'],
                      
                         ]); 
                         if ($examen == null){ 
                            $examen=new Examen();
    
                             $examen->setLibelle($request->request->all()['libelle']);
                              $examen->setPatient($request->request->all()['patient']);
                               $entityManager->persist($examen);
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

            #[Route('/{id}/edit/examen', name: 'app_examen_edit_form',methods:['GET'])]
            public function edit_form(EntityManagerInterface $entityManager,Security $security,Request $request,ExamenRepository $examenRepository,RdvsRepository $rdvsRepository,int $id): Response
                      
                {    $user=$security->getUser();
                    $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
                   if (!array_intersect($user->getRoles(), $roles)) {
                     throw new AccessDeniedException('Acces refuse');
                   } 
                   $comptes        =        $security->getUser();
                   $user           =         $comptes;
                   $login          =        $comptes->getUserIdentifier();
                   $rendez_vous    =        $rdvsRepository->findAll();
                   $data            =        $examenRepository->findBy(['id'=>$id]);
                $rendezvouslistes   =        $rdvsRepository->findBy(['emailsmedecin' =>$login]);
   
                    return $this->render('examen/edit.html.twig', [
                      'data'         =>  $data[0],
                     'rendezvousS'  =>   $rendezvouslistes,
                     'user'        => $user  
                   ]);
                
                }

                #[Route('/edit/examen/{id}', name: 'app_examen_edit',methods:['POST'])]
                public function edit(EntityManagerInterface $entityManager,Request $request,$id,ExamenRepository $examenRepository): Response
                          
                    {   
                        $examen=$examenRepository->find($id);
    
                        if  (!$examen) {
                           
                            throw $this->createNotFoundException(
                                'No product found for id '.$id
                            );
                        }
                        $examen->setLibelle($request->request->all()['libelle']);
                        $examen->setPatient($request->request->all()['patient']);
                        $entityManager->flush();
                        return $this->json([
                            'code' => 1,
                            'message' => 'mise effectue'
                        ]); 
                        
                    }





        #[Route('/delete/examen', name: 'app_examen_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $examen=$entityManager->getRepository(Examen::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($examen);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'suppresion effectué'

            ]);
        
        }


        #[Route('/examen', name: 'app_examen_liste')]
        public function liste(Security $security,ExamenRepository $examenRepository): Response
                  
            {   if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {

                return new RedirectResponse($this->generateUrl('app_login'));
               
               }else {
                $user=$security->getUser();
                $comptes  =  $user;
               $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
              if (!array_intersect($user->getRoles(), $roles)) {
                throw new AccessDeniedException('Acces refuse');
              } 

                $examen=$examenRepository->findAll();

              return $this->render('examen/index.html.twig', [
                'examens'=>$examen,
                'user'=>$user,
                'comptes'=>$comptes
            ]);
             
        }

                  
    }


   


       


    






}
