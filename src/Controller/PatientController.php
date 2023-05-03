<?php

namespace App\Controller;

use App\Entity\Patient;
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
    #[Route('/patient', name: 'app_patient')]
    public function index(PatientRepository $patientrepository, NationaliteRepository $nationaliterepository,PatientNationaliteRepository $patientnationaliterepository): Response

    {   
        $natinalite=$nationaliterepository->findAll();
        $listepatient=$patientrepository->findAll();
         $nationalitePatients=[];
        $liste_patient  = [];
        // $nat=$patientrepository->getN;
         foreach ($listepatient as $key => $patient) {
            $nat=$patient->getNationalite(); 
            $partnat = explode(",", $nat);
            foreach ($partnat as $key => $value) {
                
                $partnat[$key]= (int) trim($value); 
                $nationalite[$patient->getId()][]=$nationaliterepository->find($partnat[$key]);     
            }
         }
         
        array_push($liste_patient, ['patient' => $patient,'nationalite'=> $nationalite]);
        return $this->render('patient/index.html.twig', [
            'patient' => $listepatient,
           // 'nationalitePatient'=>$nationalitePatients,
            'nationalite'=>$natinalite
        ]);

    }

    #[Route('/ajout', name: 'app_patientad',methods:['POST','GET'])]
    public function addpatient(EntityManagerInterface $entity,Request $request,PatientRepository $patientRepository): Response

    {   
            $data= $request->request->all() ;
            $patient=new Patient();

            $patient->setNom($data['nom'] );

            $patient->setPrenom($data['prenom'] );

            $patient->setGenre($data['genre'] );

            $patient->setAge($data['age'] );

            $patient->setTelephone($data['telephone'] );

            $patient->setNationalite($data['nationalite']);
            
            $entity->persist($patient);
             $entity->flush();

          return $this->json([
            'code'=>1,
         'message'=>'insertion effectue'
        ]  
        );
           
        
                 
   
    }


    #[Route('/form', name: 'app_patientadd')]
    public function form(PatientRepository $patientrepository, NationaliteRepository $nationaliterepository,PatientNationaliteRepository $patientnationaliterepository): Response

    {    $natinalite=$nationaliterepository->findAll();
        $listepatient=$patientrepository->findAll();
        $nationalitePatients=[];
        foreach ($listepatient as $key => $value) {
           array_push($nationalitePatients,$patientnationaliterepository->findBy(['patient'=>$value->getId()])); 
        } 
        return $this->render('patient/ajout.html.twig', [
            'patient' => $listepatient,
            'nationalitePatient'=>$nationalitePatients,
            'nationalite'=>$natinalite
        ]);

    }
    #[Route('/forms/{id}', name: 'app_patients')]
    public function modifid( PatientRepository $repositorPatient,$id, NationaliteRepository $nationaliterepository,PatientNationaliteRepository $patientnationaliterepository): Response

    {   $data=$repositorPatient->findBy(['id' => $id]);
        $natinalite=$nationaliterepository->findAll();
        $listepatient=$repositorPatient->findAll();
        $nationalitePatients=[];
         foreach ($listepatient as $key => $value) {
           array_push($nationalitePatients,$patientnationaliterepository->findBy(['patient'=>$value->getId()])); 
        }
          return $this->render('patient/edit.html.twig', [
            'patient' => $listepatient,
            'nationalitePatient'=>$nationalitePatients,
            'nationalite'=>$natinalite,
            'data' =>$data[0],
        ]); 

    
    }


    #[Route('/edit/{id}', name: 'app_patientedd')]
    public function edit(EntityManagerInterface $entity,Request $request,PatientRepository $repository,$id): Response

    { 
        $specialite=$repository->find($id);

        if  (!$specialite) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $specialite->setNom($request->request->all()['nom']);
        $specialite->setPrenom($request->request->all()['prenom']);
        $specialite->setTelephone($request->request->all()['telephone']);
        $specialite->setGenre($request->request->all()['genre']);
        $specialite->setAge($request->request->all()['age']);
        $specialite->setNationalite($request->request->all()['nationalite']);
        $entity->flush();
        $response = new JsonResponse(['status' => "ok"]);
        return $response;
    }
    #[Route('/deletes', name: 'app_deletepatient')]
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


    #[Route('/delete', name: 'app_patientdelete',methods:[ 'POST'])]
     public function supprimer(EntityManagerInterface $entity,Request $request): Response
    { 
        $patient=$entity->getRepository(Patient::class)->find(['id'=>$request->request->get('id')]);
        $entity->remove($patient);
        $entity->flush();
        $response =new JsonResponse(['status' =>"ok"]);
         return $response; 
  
       
    }


}
