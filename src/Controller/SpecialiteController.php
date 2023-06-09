<?php

namespace App\Controller;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Form\SpecialiteMiseajouType;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class SpecialiteController extends AbstractController
{
    #[Route('/specialite/ajout', name: 'app_specialite_add')]
    public function ajout(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository): Response

    {  /*  $user=$this->getUser();
        $roles=['ROLE_ADMIN'];
   
       // $roles=$user->getRoles();
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       } */
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $specialite=new Specialite();

        $form=$this->createForm(SpecialiteType::class,$specialite);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $specialites=$specialiteRepository->findOneBy(['nom'=>$specialite->getNom()]);
            if ( $specialites!= null) {
                $this->addFlash('danger', 'la specialite existe deja.');  
              
            }else {
                $entityManager->persist($specialite);
                $entityManager->flush();
                return $this->redirectToRoute('app_specialite_liste');
                
            }      
            
        }

        return $this->render('specialite/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/specialite', name: 'app_specialite_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {  
           $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $examen=$entityManager->getRepository(Specialite::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($examen);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }

        #[Route('/edit/specialite/{id}', name: 'app_specialite_edit')]
        public function edit(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,$id): Response
    
        {  
           $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $specialite=$specialiteRepository->find($id);
    
            $form=$this->createForm(SpecialiteMiseajouType::class,$specialite);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $specialite = $form->getData();
                $entityManager->flush();
                $this->addFlash('success', 'la modification a reussi'); 
                return $this->redirectToRoute('app_specialite_liste'); 
          
            }
            return $this->render('specialite/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    
        }


        #[Route('/specialite', name: 'app_specialite_liste')]
        public function index(EntityManagerInterface $entity ,Request $request,SpecialiteRepository $specialiteRepository): Response
        {  
           $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $specialites=$specialiteRepository->findAll();
            return $this->render('specialite/index.html.twig', [
                'specialites' => $specialites,
            ]);
        }


   
}








 

    

