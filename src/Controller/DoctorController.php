<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Form\DoctorsType;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctor')]
    public function index(): Response
    {
        return $this->render('doctor/index.html.twig', [
            'controller_name' => 'DoctorController',
        ]);
    }

    #[Route('/doctor/add', name: 'app_doctor')]
    public function add(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository): Response
    {   

        $doctors=new Doctors();
        $specialites=$specialiteRepository->findAll();
         $specialitesfinal=[];

         foreach ($specialites as $key => $value) {
             $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
                
         }
        $form =$this->createForm(DoctorsType::class,$doctors,['specialites'=>$specialitesfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            $specialiteid=$form->get('specialite')->getData();
             $specialite=$specialiteRepository->find($specialiteid);
            $doctors->setSpecialite($specialite);
            $entityManager->persist($doctors);
            $entityManager->flush();
          }
        return $this->render('doctor/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
