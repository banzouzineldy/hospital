<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Repository\DoctorsRepository;
use App\Repository\RendezvousRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezvousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous',methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,RendezvousRepository $rendezvousRepository): Response
    {  
        $listerendezvous=$rendezvousRepository->findAll();
        
        return $this->render('rendezvous/index.html.twig', [
            'rendezvous' =>$listerendezvous
        ]);
    }


       
    #[Route('/rendezvous/add', name: 'app_rendezvous_add',methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,DoctorsRepository $doctorsRepository): Response
    {   

        $rendezvous=new Rendezvous();
        $specialites=$specialiteRepository->findAll();
        $doctors=$doctorsRepository->findAll();
        $doctorsfinal=[];
        $specialitesfinal=[];
    
        foreach ($specialites as $key => $value) {
            $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
               
        }
        foreach ( $doctors as $key => $value) {
            $doctorsfinal=array_merge($doctorsfinal ,[$value->getNom(). '  ' .$value->getPrenom()=>$value->getId()]);
            
        }
         $form =$this->createForm(RendezvousType::class,$rendezvous,['specialites'=>$specialitesfinal,'doctors'=> $doctorsfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 

            $specialiteid=$form->get('specialite')->getData();
           
            $specialite=$specialiteRepository->find($specialiteid);
            $doctorsid=$form->get('doctors')->getData();
            $doctor=$doctorsRepository->find( $doctorsid);
            $rendezvous->setSpecialite($specialite);
            $rendezvous->setDoctors($doctor);

            $entityManager->persist($rendezvous);

            $entityManager->flush();

            return $this->redirectToRoute('app_rendezvous');
          }
        return $this->render('rendezvous/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }






}
