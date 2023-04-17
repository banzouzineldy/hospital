<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Repository\DoctorsRepository;
use App\Repository\PatientRepository;
use App\Repository\RendezvousRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlagehoraireController extends AbstractController
{


    #[Route('/plagehoraire', name: 'app_plagehoraire')]
    public function index(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository, RendezvousRepository $rdvrepository): Response
    {  $patient=$patientRepository->findAll();
        $doctor=$doctors->findAll();
        $specialite=$specialiteRepository->findAll();
        $rdv=$rdvrepository->findAll();
        return $this->render('plagehoraire/index.html.twig', [
            'patient'=>$patient,
             'doctor'=>$doctor,
             'specialite'=>$specialite,
             'rdv'=>$rdv
            
        ]);
       
    }
    
    #[Route('/ajouthoraire', name: 'app_plagehoraires',methods:['POST'])]
    public function ajout(EntityManagerInterface $entityManager, Request $request): Response
    { 
       $rendezvous= new Rendezvous();
      /*  $datestart= new \DateTime($request->get('start'));
       $dateString=$datestart->format('Y-m-d H:i:s');
       $dateEnd=new \DateTime($request->get('end'));
       $dateEndString= $dateEnd->format('Y-m-d H:i:s'); */
        $rendezvous->setTitle($request->request->all()['title']);
        $rendezvous->setStart( new \DateTime ($request->request->all()['start']));
        $rendezvous->setEnd( new \DateTime ($request->request->all()[ 'end']));
        $rendezvous->setPatient($request->request->all()['patient']);
        $rendezvous->setSpecialite($request->request->all()['specialite']);
        $rendezvous->setDoctor($request->request->all()['doctor']);
        $rendezvous->setMotif($request->request->all()['motif']);
        $rendezvous->setBackground($request->request->all()['background']);
        $rendezvous->setTextcolor($request->request->all()['textcolor']);
        $entityManager->persist($rendezvous);
        $entityManager->flush();
        return $this->json([
            'code' =>1 ,
            'message' => 'insertion effectuée'
        ]); 
    }

    #[Route('/plageajout', name: 'app_plagehoraireajout')]
    public function formulaire(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository): Response
    {  $patient=$patientRepository->findAll();
        $doctor=$doctors->findAll();
        $specialite=$specialiteRepository->findAll();
       // dd($doctor);
        return $this->render('plagehoraire/ajout.html.twig', [
            'patient'=>$patient,
             'doctor'=>$doctor,
               'specialite'=>$specialite
            
        ]);
    }


    #[Route('/{id}/rdv', name: 'app_rendevoushoraire')]
    public function edit(EntityManagerInterface $entity,Request $request,RendezvousRepository $repository,$id): Response

    {   
         $rendezvous=$repository->find($id);

        if  (!$rendezvous) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $datestart= new \DateTime($request->get('start'));
        $dateString=$datestart->format('Y-m-d H:i:s');
        $dateEnd=new \DateTime($request->get('end'));
        $dateEndString= $dateEnd->format('Y-m-d H:i:s');
         $rendezvous->setTitle($request->request->all()['title']);
        // $rendezvous->setStart($request->request->all()[$dateString]);
        // $rendezvous->setEnd($request->request->all()[ $dateEndString]);
         $rendezvous->setPatient($request->request->all()['patient']);
         $rendezvous->setSpecialite($request->request->all()['specialite']);
         $rendezvous->setDoctor($request->request->all()['doctor']);
         $rendezvous->setMotif($request->request->all()['motif']);
         $rendezvous->setBackground($request->request->all()['background']);
         $rendezvous->setTextcolor($request->request->all()['textcolor']);
          $entity->flush();
        return $this->json([
            'code'=>1,
             'message'=>'Mise a jour effectué'
    
             ]);

    }

    #[Route('/{id}/plage/rendevous', name: 'app_rendez_vousliste')]
    public function forme(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository,RendezvousRepository $rdv,$id): Response
    {  $patient=$patientRepository->findAll();
        $doctor=$doctors->findAll();
        $data=$rdv->findBy(['id' => $id]);
       $specialite=$specialiteRepository->findAll();
        return $this->render('plagehoraire/edit.html.twig', [
            'patient'=>$patient,
             'doctor'=>$doctor,
            'specialite'=>$specialite,
               'data'=>$data[0]
            
        ]);
        
    }

    #[Route('/deleterendezvous', name: 'app_delete_rendez_vous')]
 public function delete(EntityManagerInterface $entityManager,Request $request): Response
           
     {  
         $rendezvous=$entityManager->getRepository(Rendezvous::class)->find(
        ['id'=>$request->request->get('id')]) ;
       $entityManager->remove($rendezvous);
       $entityManager->flush();
     
        return $this->json([
        'code'=>1,
         'message'=>'Mise a jour effectué'

         ]);
     
     }


     #[Route('/modalhoraire', name: 'app_plagehoraires_modal',methods:['POST'])]
     public function modalhoraire(EntityManagerInterface $entityManager, Request $request): Response
     { 
        $rendezvous= new Rendezvous();
       /*  $datestart= new \DateTime($request->get('start'));
        $dateString=$datestart->format('Y-m-d H:i:s');
        $dateEnd=new \DateTime($request->get('end'));
        $dateEndString= $dateEnd->format('Y-m-d H:i:s'); */
         $rendezvous->setTitle($request->request->all()['title']);
         $rendezvous->setStart( new \DateTime ($request->request->all()['start']));
         $rendezvous->setEnd( new \DateTime ($request->request->all()[ 'end']));
         $rendezvous->setPatient($request->request->all()['patient']);
         $rendezvous->setSpecialite($request->request->all()['specialite']);
         $rendezvous->setDoctor($request->request->all()['doctor']);
         $rendezvous->setMotif($request->request->all()['motif']);
         $rendezvous->setBackground($request->request->all()['']);
         $rendezvous->setTextcolor($request->request->all()['']);
         $entityManager->persist($rendezvous);
         $entityManager->flush();
         return $this->json([
             'code' =>1 ,
             'message' => 'insertion effectuée'
         ]); 
     }





}
