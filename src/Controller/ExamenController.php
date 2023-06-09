<?php

namespace App\Controller;

use Exception;
use App\Entity\Examen;
use App\Form\ExamenMiseAjourType;
use App\Form\ExamenType;
use App\Repository\ExamenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExamenController extends AbstractController
{
    #[Route('/examen/ajout', name: 'app_examen_add')]
    public function index(EntityManagerInterface $entityManager,ExamenRepository $examenRepository,Request $request): Response
    {
        
        $examen=new Examen();
        $form=$this->createForm(ExamenType::class,$examen);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            try {
                $examens=$examenRepository->findOneBy(['libelle'=>$examen->getLibelle()]);
                if ( $examens!= null) {
                    $this->addFlash('danger', 'lexamen existe deja.');  
                  
                }else {
                    $entityManager->persist($examen);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_examen_liste');
                    
                }      
                              
            } catch (Exception $e) {
                //throw $th;
                dd($e->getMessage());
            }
           
        }
        return $this->render('examen/ajout.html.twig', [
            'form' => $form->createView(),
           
             
         ]);
        }

         #[Route('/edit/examen/{id}', name: 'app_examen_edit',methods:['GET','POST'])]
         public function edit(EntityManagerInterface $entityManager,Request $request,ExamenRepository $examenRepository,$id): Response
     
         { 
             $examen=$examenRepository->find($id);
     
             $form=$this->createForm(ExamenMiseAjourType::class,$examen);
             $form->handleRequest($request);
             
             if ($form->isSubmitted() && $form->isValid()) { 
                 $specialite = $form->getData();
                 $entityManager->flush();
                 $this->addFlash('success', 'la modification a reussi'); 
                 return $this->redirectToRoute('app_specialite_liste'); 
           
             }
             return $this->render('examen/edit.html.twig', [
                 'form' => $form->createView(),
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
        public function liste(ExamenRepository $examenRepository): Response
                  
            {   
                $examen=$examenRepository->findAll();

              return $this->render('examen/index.html.twig', [
                'examens'=>$examen
            ]);
            
             


            
            }


   


       


    






}
