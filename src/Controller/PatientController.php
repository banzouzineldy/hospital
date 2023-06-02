<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatienatMiseAjourType;
use App\Form\PatientType;
use App\Repository\NationaliteRepository;
use App\Repository\PatientNationaliteRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController

{
    #[Route('/patient', name: 'app_patient_liste')]
    public function index(PatientRepository $patientrepository, NationaliteRepository $nationaliterepository,PatientNationaliteRepository $patientnationaliterepository): Response

    {   
         $patients=$patientrepository->findAll();
         return $this->render('patient/index.html.twig', [
            'patients'=>$patients
        ]);  
         

    }

    #[Route('/ajout/patient', name: 'app_patient_add',methods:['POST','GET'])]
    public function addpatient(EntityManagerInterface $entityManager,Request $request,NationaliteRepository $nationaliteRepository,PatientRepository $patientRepository): Response

    {   $patient=new Patient();
        $nationalites=$nationaliteRepository->findAll();

        $nationalitefinal=[];

        foreach ($nationalites as $key => $value) {
            $nationalitefinal=array_merge( $nationalitefinal,[$value->getNom()=>$value->getId()]);
               
        }
        $form=$this->createForm(PatientType::class,$patient,['Nationalites'=>$nationalitefinal]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
             $nationaliteid=$form->get('nationalite')->getData();
             $nationalite=$nationaliteRepository->find($nationaliteid);
           $patient->setNationalite($nationalite);
            $patientes=$patientRepository->findOneBy(['nom'=>$patient->getNom(),
            'telephone'=>$patient->getTelephone()]);
            if ( $patientes!= null) {
               $this->addFlash('danger', 'ce patient existe deja.');  
             
            }
            else {
                $entityManager->persist($patient);
                $entityManager->flush();
                return $this->redirectToRoute('app_patient_liste');
                
                
            }

            return $this->redirectToRoute('app_patient_liste');
            
          }

          return $this->render('patient/ajout.html.twig', [
            'form' => $form->createView(),
        ]);                      
   
    }


    #[Route('/edit/patient/{id}', name: 'app_patient_edit',methods:['POST','GET'])]
    public function edit(EntityManagerInterface $entityManager,Request $request,PatientRepository $patientRepository,$id,NationaliteRepository $nationaliteRepository): Response

    { 
        $patient=$patientRepository->find($id);

        $nationalites=$nationaliteRepository->findAll();

        $nationalitefinal=[];

        foreach ($nationalites as $key => $value) {
            $nationalitefinal=array_merge( $nationalitefinal,[$value->getNom()=>$value->getId()]);
               
        }
        $form=$this->createForm(PatienatMiseAjourType::class,$patient,['Nationalites'=>$nationalitefinal]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            //$nationaliteid->get('nationalite')->getData();
           // $nationalite=$nationaliteRepository->find($nationaliteid);
           // $patient->setNationalite($nationalite);
            $entityManager->flush();
                // do anything else you need here, like send an email

            return $this->redirectToRoute('app_patient_liste');
            
         }

        return $this->render('patient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
            
       
    }
    #[Route('/deletes/patient', name: 'app_patient_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              

        {   $patient=$entityManager->getRepository(Patient::class)->find(
            ['id'=>$request->request->get('id')]) ;
           $entityManager->remove( $patient);
           $entityManager->flush();
         
            return $this->json([
            'code'=>1,
             'message'=>'suppression effectué'
 
             ]);


        }



}
