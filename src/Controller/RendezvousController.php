<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Entity\Rdv;
use App\Repository\RdvRepository;
use App\Repository\DoctorsRepository;
use App\Repository\PatientRepository;
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
    public function index(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository,RdvRepository $rdvrepository): Response
    {  $patient=$patientRepository->findAll();
        $doctor=$doctors->findAll();
        $specialite=$specialiteRepository->findAll();
        $rdv=$rdvrepository->findAll();
        return $this->render('rendezvous/index.html.twig', [
            'patient'=>$patient,
             'doctor'=>$doctor,
             'specialite'=>$specialite,
             'rdv'=>$rdv
            
        ]);
       
    }
    #[Route('/show/rdv', name: 'app_rendezvousliste')]
    public function form(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository): Response
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


    #[Route('/ajout/rdv', name: 'app_rdvedds')]
    public function ajout(EntityManagerInterface $entity,Request $request): Response

    {   
        $rdv=new Rdv();
        $rdv->setHeure($request->request->all()['heure']);
        //$user->setPrenom($data->prenom);
        $rdv->setPatient($request->request->all()['patient']);
       // $user->setEmail($request->request->all()['nom']);
        $rdv->setDoctor($request->request->all()['doctor']);
        //$user->setDatenaissance($data->datenaissance);
        $rdv->setDate($request->request->all()['date']);
        $rdv->setSpecialite($request->request->all()['specialite']);
        $rdv->setAllday($request->request->all()['allday']);
        $rdv->setBackground($request->request->all()['background']);
        $rdv->setBorder($request->request->all()['border']);
        $rdv->setTextcolor($request->request->all()['textcolor']);
       //  $user->setPays($data->pays);
       $entity->persist($rdv);
        $entity->flush();
        return $this->json([
            'code' => 1,
            'message' => 'insertion effectuée'
        ]);
       

    }


    #[Route('/{id}/rdv', name: 'app_rdvedd')]
    public function edit(EntityManagerInterface $entity,Request $request,RdvRepository $repository,$id): Response

    {   
        $user=$repository->find($id);

        if  (!$user) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $user->setHeure($request->request->all()['heure']);
        //$user->setPrenom($data->prenom);
        $user->setPatient($request->request->all()['patient']);
       // $user->setEmail($request->request->all()['nom']);
        $user->setDoctor($request->request->all()['doctor']);
        //$user->setDatenaissance($data->datenaissance);
        $user->setDate($request->request->all()['date']);
        $user->setSpecialite($request->request->all()['specialite']);
        $entity->flush();
        return $this->json([
            'code'=>1,
             'message'=>'Mise a jour effectué'
    
             ]);

 }
 #[Route('/deleterdv', name: 'app_delete_rdv')]
 public function delete(EntityManagerInterface $entityManager,Request $request): Response
           
     {  
         $patient=$entityManager->getRepository(Rdv::class)->find(
        ['id'=>$request->request->get('id')]) ;
       $entityManager->remove($patient);
       $entityManager->flush();
     
        return $this->json([
        'code'=>1,
         'message'=>'Mise a jour effectué'

         ]);
     
     }
   
 #[Route('/{id}/edit/rdv', name: 'app_rendezvouslistes')]
    public function forme(EntityManagerInterface $entityManager,PatientRepository $patientRepository,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository,RdvRepository $rdv,$id): Response
    {  $patient=$patientRepository->findAll();
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
