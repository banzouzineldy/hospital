<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Form\DoctorsMiseAjourType;
use App\Form\DoctorsType;
use App\Repository\DoctorsRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctor_liste')]
    public function index(EntityManagerInterface $entityManager, DoctorsRepository $doctorsRepository): Response
    { $specialitefinal=[];
        $doctors=$doctorsRepository->findAll();

       //$skprecialithre=$doctors->getSpecialite()->getNom();
       /*    foreach ($variable as $key => $value) {
            
          } */
        
        return $this->render('doctor/index.html.twig', [
           'doctor' =>$doctors,
           //'specialite' =>$specialite
        ]);
    }

    #[Route('/doctor/add', name: 'app_doctor_add',methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger): Response
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
           
            $membre = $entityManager->getRepository(Doctors::class)->findOneBy([
                'email'=>$doctors->getEmail(),
                 'telephone'=>$doctors->getTelephone()
             ]);
             if($membre != null){
                 dd('le membre existe deja');
 
             }

            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $doctors->setFile($newFilename);
            }
            $specialiteid=$form->get('specialite')->getData();
             $specialite=$specialiteRepository->find($specialiteid);
            $doctors->setSpecialite($specialite);
             $genre=$form->get('genre')->getData();
             $datenaissance=$form->get('datenaissance')->getData();
            $entityManager->persist($doctors);
            $entityManager->flush();
            return $this->redirectToRoute('app_doctor_liste');
          }
        return $this->render('doctor/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/doctor/{id}', name: 'app_doctor_update',methods: [ 'POST','GET'])]
    public function  update(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger,int $id): Response
    {   

        $user=$entityManager->getRepository(Doctors::class)->find($id);
        $specialites=$specialiteRepository->findAll();
         $specialitesfinal=[];

         foreach ($specialites as $key => $value) {
             $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
                
         }
        $form =$this->createForm(DoctorsMiseAjourType::class,$user,['specialites'=>$specialitesfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                 $user->setFile($newFilename);
            }
            $specialiteid=$form->get('specialite')->getData();
             $specialite=$specialiteRepository->find($specialiteid);
            $user->setSpecialite($specialite);
            $genre=$form->get('genre')->getData();
            $datenaissance=$form->get('datenaissance')->getData();
            $entityManager->flush();
            return $this->redirectToRoute('app_doctor_liste');
          }
        return $this->render('doctor/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_doctor_delete')]
    public function delete(EntityManagerInterface $entityManager,int $id,Request $request): Response
              


        {   $doctor=$entityManager->getRepository(Doctors::class)->find(
            ['id'=>$request->request->get('id')])
             ;
           /*   if (!$doctor) {
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            } */
           $entityManager->remove( $doctor);
           $entityManager->flush();
         
            return $this->json([
            'code'=>1,
             'message'=>'suppressio effectué'
 
             ]);


        }




}
