<?php

namespace App\Controller;

use App\Entity\Hospitalisation;
use App\Form\HospitalisationType;
use App\Repository\LitRepository;
use App\Repository\UserRepository;
use App\Repository\ChambreRepository;
use App\Repository\PatientRepository;
use App\Repository\ServiceRepository;
use App\Repository\PathologieRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\HospitalisationMiseAJourType;
use App\Repository\HospitalisationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HospitalisationController extends AbstractController
{
    
     
    #[Route('/hospitalisation/add', name: 'app_hospitalisation_add',methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request,ServiceRepository $serviceRepository,PathologieRepository $pathologieRepository,PatientRepository $patientRepository,UserRepository $agentRepository,ChambreRepository $chambreRepository,LitRepository $litRepository,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger): Response
    {   

        $hospitalisations=new Hospitalisation();
        $services=$serviceRepository->findAll();
        $pathologies=$pathologieRepository->findAll();
        $patients=$patientRepository->findAll();
        $agents=$agentRepository->findAll();
        $chambres=$chambreRepository->findAll();
        $lits=$litRepository->findAll();
        $specialites=$specialiteRepository->findAll();
         $servicesfinal=[];
         $servicesfinal=array_merge( $servicesfinal ,['selectionner une option'=>0]);
         $pathologiesfinal=[];
         $patientsfinal=[];
         $agentsfinal=[];
         $chambresfinal=[];
         $litsfinal=[];
         $specialitesfinal=[];
        

         foreach ($services as $key => $value) {
             $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
                
         }
         foreach ($pathologies as $key => $value) {
            $pathologiesfinal=array_merge( $pathologiesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        foreach ($patients as $key => $value) {
            $patientsfinal=array_merge( $patientsfinal ,[$value->getNom().$value->getPrenom()=>$value->getId()]);
               
        }

        foreach ($agents as $key => $value) {
            $agentsfinal=array_merge( $agentsfinal ,[$value->getNom().$value->getPrenom()=>$value->getId()]);
               
        }
        foreach ($chambres as $key => $value) {
            $chambresfinal=array_merge( $chambresfinal ,[$value->getNumChambre()=>$value->getId()]);
               
        }
        foreach ($lits as $key => $value) {
            $litsfinal=array_merge( $litsfinal ,[$value->getNumLit()=>$value->getId()]);
               
        }
        foreach ($specialites as $key => $value) {
            $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
               
        }
        $form =$this->createForm(HospitalisationType::class,$hospitalisations,['services'=>$servicesfinal,'pathologies'=>$pathologiesfinal,'lits'=>$litsfinal,'patients'=>$patientsfinal,'chambres'=>$chambresfinal,'agents'=>$agentsfinal,'specialites'=>$specialitesfinal]);
       
        
          $form->handleRequest($request);

          
    
          if ($form->isSubmitted() && $form->isValid()) { 
           
            

            
            $serviceid=$form->get('service')->getData();
            $pathologieid=$form->get('pathologie')->getData();
            $patientid=$form->get('patient')->getData();
            $agentid=$form->get('agent')->getData();
            $chambreid=$form->get('chambre')->getData();
            $litid=$form->get('lit')->getData();
            $specialiteid=$form->get('specialite')->getData();
             $service=$serviceRepository->find($serviceid);
             $pathologie=$pathologieRepository->find($pathologieid);
             $patient=$patientRepository->find($patientid);
             $agent=$agentRepository->find($agentid);
             $chambre=$chambreRepository->find($chambreid);
             $lit=$litRepository->find($litid);
             $specialite=$specialiteRepository->find($specialiteid);
            $hospitalisations->setService($service);
            $hospitalisations->setPathologie($pathologie);
            $hospitalisations->setChambre($chambre);
            $hospitalisations->setLit($lit);
            $hospitalisations->setAgent($agent);
            $hospitalisations->setPatient($patient);
            $hospitalisations->setSpecialite($specialite);
            $dateEntree =$form->get('dateEntree')->getData();
            //$dateSortie=$form->get('dateSortie')->getData();
            $motifAdmission=$form->get('motifAdmission')->getData();
            $typeAdmission=$form->get('typeAdmission')->getData();
           // $motifSortie=$form->get('motifSortie')->getData();
            $hospitalisations->setDateEntree($dateEntree);
           // $hospitalisations->setDateSortie($dateSortie);
            $hospitalisations->setMotifAdmission($motifAdmission);
            $hospitalisations->setTypeAdmission($typeAdmission);
           // $hospitalisations->setMotifSortie($motifSortie);
            $entityManager->persist($hospitalisations);
            $entityManager->flush();
            return $this->redirectToRoute('app_hospitalisation');
          }
        return $this->render('hospitalisation/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('/hospitalisation', name: 'app_hospitalisation')]
    public function index(EntityManagerInterface $entityManager,HospitalisationRepository $hospitalisationRepository): Response
    {
        
        $hospitalisations=$hospitalisationRepository->findAll();
        return $this->render('hospitalisation/index.html.twig', [
            'controller_name' => 'hospitalisationController',
            'hospitalisation' =>$hospitalisations,
        ]);
    }



    #[Route('/hospitalisation/{id}', name: 'app_update_hospitalisation',methods: [ 'POST','GET'])]
    public function  update(EntityManagerInterface $entityManager,Request $request,ServiceRepository $serviceRepository,PathologieRepository $pathologieRepository,PatientRepository $patientRepository,UserRepository $agentRepository,ChambreRepository $chambreRepository,LitRepository $litRepository,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger,int $id): Response
    {   

        $user=$entityManager->getRepository(Hospitalisation::class)->find($id);
        $services=$serviceRepository->findAll();
        $pathologies=$pathologieRepository->findAll();
        $patients=$patientRepository->findAll();
        $agents=$agentRepository->findAll();
        $chambres=$chambreRepository->findAll();
        $lits=$litRepository->findAll();
        $specialites=$specialiteRepository->findAll();
         $servicesfinal=[];
         $pathologiesfinal=[];
         $patientsfinal=[];
         $agentsfinal=[];
         $chambresfinal=[];
         $litsfinal=[];
         $specialitesfinal=[];
         
         foreach ($services as $key => $value) {
            $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        foreach ($pathologies as $key => $value) {
           $pathologiesfinal=array_merge( $pathologiesfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       foreach ($patients as $key => $value) {
           $patientsfinal=array_merge( $patientsfinal ,[$value->getNom().$value->getPrenom()=>$value->getId()]);
              
       }

       foreach ($agents as $key => $value) {
           $agentsfinal=array_merge( $agentsfinal ,[$value->getNom().$value->getPrenom()=>$value->getId()]);
              
       }
       foreach ($chambres as $key => $value) {
           $chambresfinal=array_merge( $chambresfinal ,[$value->getNumChambre()=>$value->getId()]);
              
       }
       foreach ($lits as $key => $value) {
           $litsfinal=array_merge( $litsfinal ,[$value->getNumLit()=>$value->getId()]);
              
       }
       foreach ($specialites as $key => $value) {
           $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
              
       }
        
         
        $form =$this->createForm(HospitalisationMiseAJourType::class,$user,['services'=>$servicesfinal,'pathologies'=>$pathologiesfinal,'lits'=>$litsfinal,'patients'=>$patientsfinal,'chambres'=>$chambresfinal,'agents'=>$agentsfinal,'specialites'=>$specialitesfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            
            $serviceid=$form->get('service')->getData();
            $pathologieid=$form->get('pathologie')->getData();
            $patientid=$form->get('patient')->getData(); 
            $agentid=$form->get('agent')->getData();
            $chambreid=$form->get('chambre')->getData();
            $litid=$form->get('lit')->getData();
            $specialiteid=$form->get('specialite')->getData();
             $service=$serviceRepository->find($serviceid);
             $pathologie=$pathologieRepository->find($pathologieid);
             $patient=$patientRepository->find($patientid);
             $agent=$agentRepository->find($agentid);
             $chambre=$chambreRepository->find($chambreid);
             $lit=$litRepository->find($litid);
             $specialite=$specialiteRepository->find($specialiteid);
            $user->setService($service);
            $user->setPathologie($pathologie);
            $user->setPatient($patient);
            $user->setChambre($chambre);
            $user->setAgent($agent);
            $user->setLit($lit);
            $user->setSpecialite($specialite);
            $dateEntree =$form->get('dateEntree')->getData();
            $dateSortie=$form->get('dateSortie')->getData();
            $motifAdmission=$form->get('motifAdmission')->getData();
            $typeAdmission=$form->get('typeAdmission')->getData();
            $motifSortie=$form->get('motifSortie')->getData();
            $user->setDateEntree($dateEntree);
            $user->setDateSortie($dateSortie );
            $user->setMotifAdmission($motifAdmission);
            $user->setTypeAdmission($typeAdmission);
            $user->setMotifSortie($motifSortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_hospitalisation');
          }
        return $this->render('hospitalisation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    } 


    #[Route('/delete/hospitalisation', name: 'app_delete_hospitalisation')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              


    {   
        $service=$entityManager->getRepository(Hospitalisation::class)->find(
       ['id'=>$request->request->get('id')]) ;
      $entityManager->remove($service);
      $entityManager->flush();
    
       return $this->json([
       'code'=>1,
        'message'=>'Mise a jour effectué'

        ]);


        
    
    }




    #[Route('api/hospitalisation/filtre', name: 'app_hospitalisation_filtre_agent',methods:['POST'])]
    public function filtre(SpecialiteRepository $specialiteRepository ,Request $request): Response
    {  
        $specialitefiltre=[];
        $data=json_decode($request->getContent(),false);
         if (empty($data->id)) {
          return $this->json(['code'=>'l id est introuvable']);
          
         }
        
         $userliste=$specialiteRepository->find(['id'=>$data->id]);
         //recuperation des utilisateurs
         $userfiltre=$userliste->getUser();
         $utilisateursfinal= [];

        foreach ($userfiltre as $value) {
          $roles=$value->getRoles();

          if (in_array("ROLE_MEDECIN",$roles)) {
            array_push( $utilisateursfinal,$value);
            
          }
          
        }

         foreach ( $utilisateursfinal as $key => $value) {
          array_push($specialitefiltre,['nom'=>$value->getNom().' ' .$value->getPrenom(),'id'=>$value->getEmail()]);
               
        }
         return $this->json(['code'=>1,
         'utilisateur'=>$specialitefiltre]);
            
            
            
        
    }




}
