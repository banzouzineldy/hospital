<?php

namespace App\Controller;

use Exception;
use App\Entity\ActeMedical;
use App\Form\ActeMedicalMiseAjourType;
use App\Form\ActeMedicalType;
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
    #[Route('/acte/medical', name: 'app_acte_medical')]


    public function index(Request $request,ActeMedicalRepository $acteMedicalRepository ,EntityManagerInterface $entityManager): Response
    {    
        $acte=new ActeMedical();
        $form=$this->createForm(ActeMedicalType::class,$acte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            try {
                $examens=$acteMedicalRepository->findOneBy(['libelle'=>$acte->getLibelle()]);
                if ( $examens!= null) {
                    $this->addFlash('danger','l actemedical existe deja.');  
                  
                }else {
                    $entityManager->persist($acte);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_acteMedical_liste');    
                }      
                              
            } catch (Exception $e) {
                //throw $th;
                dd($e->getMessage());
            }
           
        }
             return $this->render('acte_medical/ajout.html.twig', [
            'form' => $form->createView(),   
             
         ]);

        
  
        }
        #[Route('acteMedical/edit/{id}', name: 'app_acteMedical_edit',methods:['GET','POST'])]
        public function edit(EntityManagerInterface $entityManager,Request $request,ActeMedicalRepository $acteMedicalRepository,$id): Response
    
        { 
            $acte=$acteMedicalRepository->find($id);
    
            $form=$this->createForm(ActeMedicalMiseAjourType::class,$acte);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $specialite = $form->getData();
                $entityManager->flush();
                $this->addFlash('success', 'la modification a reussi'); 
                return $this->redirectToRoute('app_acteMedical_liste'); 
          
            }
            return $this->render('acte_medical/edit.html.twig', [
                'form' => $form->createView(),
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

            #[Route('/acte/liste', name: 'app_acteMedical_liste')]
            public function liste(Security $security,ActeMedicalRepository $acteMedicalRepository): Response
                      
                {   if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
    
                    return new RedirectResponse($this->generateUrl('app_login'));
                   
                   }else {
                    $user=$security->getUser();
                   $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
                  if (!array_intersect($user->getRoles(), $roles)) {
                    throw new AccessDeniedException('Acces refuse');
                  } 
    
                    $acte=$acteMedicalRepository->findAll();
    
                  return $this->render('acte_medical/index.html.twig', [
                    'actes'=>$acte,
                    'user'=>$user
                ]);
                 
            }

        }



}
