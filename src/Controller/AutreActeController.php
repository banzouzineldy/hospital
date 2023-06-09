<?php

namespace App\Controller;

use App\Entity\ActeMedical;
use Exception;
use App\Entity\AutreActemedical;
use App\Form\ActeMedicalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActeMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutreActeController extends AbstractController
{
    #[Route('/autre/acte', name: 'app_autre_acte')]
    public function index(Request $request,ActeMedicalRepository $acteMedicalRepository ,EntityManagerInterface $entityManager): Response
    {    
        $acte=new AutreActemedical();
        $form=$this->createForm(ActeMedicalType::class,$acte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            try {
                $examens=$acteMedicalRepository->findOneBy(['libelle'=>$acte->getLibelle()]);
                if ( $examens!= null) {
                    $this->addFlash('danger', 'lexamen existe deja.');  
                  
                }else {
                    $entityManager->persist($acte);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_acte_liste');
                    
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
    
            $form=$this->createForm(ExamenMiseAjourType::class,$acte);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $specialite = $form->getData();
                $entityManager->flush();
                $this->addFlash('success', 'la modification a reussi'); 
                return $this->redirectToRoute('app_acte_liste'); 
          
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




}
