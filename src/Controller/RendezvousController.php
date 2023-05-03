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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezvousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(): Response
    {  
        return $this->render('rendezvous/index.html.twig', [
           
        ]);
    }


    #[Route('/ajout/rendezvous/add', name: 'app_rendezvous_add')]
    public function ajout(EntityManagerInterface $entity,Request $request): Response

    {   
        $rendezvous = new Rendezvous();
        $rendezvous->setDate(new \DateTime($request->request->all()['date']));
        $rendezvous->setPatient($request->request->all()['patient']);
       // $user->setEmail($request->request->all()['nom']);
       $rendezvous->setDoctors($request->request->all()['doctors']);
        //$user->setDatenaissance($data->datenaissance);
        $rendezvous->setDateFin(new \DateTime ($request->request->all()['dateFin']));
        $rendezvous->setSpecialite($request->request->all()['specialite']);
       //  $user->setPays($data->pays);
       $entity->persist( $rendezvous);
        $entity->flush();
        return $this->json([
            'code' => 1,
            'message' => 'insertion effectuée'
        ]);
       

    }


    #[Route('/rendezvous/{id}', name: 'app_patientedd')]
    public function edit(EntityManagerInterface $entity,Request $request,RendezvousRepository $rendezvousRepository,$id): Response

    { 
        $rendezvous=$rendezvousRepository->find($id);

        if  (!$rendezvousRepository) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $rendezvous->setDate(new \DateTime($request->request->all()['date']));
        $rendezvous->setPatient($request->request->all()['patient']);
       $rendezvous->setDoctors($request->request->all()['doctors']);
        $rendezvous->setDateFin(new \DateTime ($request->request->all()['dateFin']));
        $rendezvous->setSpecialite($request->request->all()['specialite']);
        $entity->flush();
        $response = new JsonResponse(['status' => "ok"]);
        return $response;
    }





    #[Route('/delete/rendezvous', name: 'app_rendezvous_delete',methods:[ 'POST'])]
    public function supprimer(EntityManagerInterface $entity,Request $request): Response
   { 
       $rendezvous=$entity->getRepository(Rendezvous::class)->find(['id'=>$request->request->get('id')]);
       $entity->remove($rendezvous);
       $entity->flush();
       $response =new JsonResponse(['status' =>"ok"]);
        return $response; 
 
      
   }

   #[Route('/formulaire/rendezvous', name: 'app_rendezvous_forme')]
   public function formrendezvous(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository): Response
   {  $patient=$patientRepository->findAll();
       $doctor=$doctors->findAll();
       $specialite=$specialiteRepository->findAll();
      // dd($doctor);
       return $this->render('rendezvous/ajout.html.twig', [
           'patient'=>$patient,
            'doctor'=>$doctor,
              'specialite'=>$specialite
           
       ]);
   }


   #[Route('/{id}/edit/rendezvous', name: 'app_rendezvouslistes')]
   public function forme(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository,RendezvousRepository $rdv,$id): Response
   {  
      $patient=$patientRepository->findAll();
       $doctor=$doctors->findAll();
       $data=$rdv->findBy(['id' => $id]);
       $specialite=$specialiteRepository->findAll();
      // dd($doctor);
       return $this->render('rendezvous/edit.html.twig', [
           'patient'=>$patient,
            'doctor'=>$doctor,
              'specialite'=>$specialite,
              'data'=>$data[0]
           
       ]);
   }
   





}
