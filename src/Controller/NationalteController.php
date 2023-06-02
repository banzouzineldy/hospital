<?php

namespace App\Controller;

use App\Entity\Nationalite;
use App\Entity\Specialite;
use App\Form\NationalitemiseajourType;
use App\Form\NationaliteType;
use App\Form\SpecialiteMiseajouType;
use App\Form\SpecialiteType;
use App\Repository\NationaliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class NationalteController extends AbstractController
{
    #[Route('/nationalite', name: 'app_nationalite')]
    public function index(NationaliteRepository $nationaliteRepository): Response

    {  $nationalites=$nationaliteRepository->findAll();
        return $this->render('nationalite/index.html.twig', [
            'nationalites' =>$nationalites,
        ]);
    }


    #[Route('/nationalite/ajout', name: 'app_nationalite_add',methods:['POST','GET'])]
    public function ajout(EntityManagerInterface $entityManager,Request $request,NationaliteRepository $nationaliteRepository): Response

    {  
        $nationalite=new Nationalite();

        $form=$this->createForm(NationaliteType::class,$nationalite);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $nationalite = $form->getData();
            $nationalites=$nationaliteRepository->findOneBy(['nom'=>$nationalite->getNom()]);

            if ($nationalites!=null) {
               
                $this->addFlash('danger', 'la specialite existe deja.');  
            }
            else {
                $entityManager->persist($nationalite);
                $entityManager->flush(); 
                
                return $this->redirectToRoute('app_specialite_liste');
                
            }      
            
        }
        return $this->render('nationalite/ajout.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/nationalite/edit', name: 'app_nationalite_edit',methods:['POST','GET'])]
    public function modification(EntityManagerInterface $entityManager,Request $request,NationaliteRepository $nationaliteRepository,int $id): Response

    {  
        $nationalite=$nationaliteRepository->find($id);

        $form=$this->createForm(NationalitemiseajourType::class,$nationalite);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $nationalite = $form->getData();
            $entityManager->flush();

            
        }

        return $this->render('nationalite/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/nationalite', name: 'app_nationalite_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $nationalite=$entityManager->getRepository(Nationalite::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove( $nationalite);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'suppression effectué'

            ]);
        
        }




}
