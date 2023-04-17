<?php

namespace App\Controller;

use App\Entity\Doctors;
use PhpParser\Builder\Method;
use App\Repository\DoctorsRepository;
use App\Repository\PatientRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctors')]
    public function index(EntityManagerInterface $entity ,Request $request,SpecialiteRepository $specialiteRepository): Response
    { 
        $specialite=$specialiteRepository->findAll();
   /*      return $this->render('doctor/ajout.html.twig', [
            'specialite' => $specialite,
        ]); */

        return $this->json(['specialite' => $specialite]);
    }
    #[Route('/home', name: 'app_doctors_home')]
    public function home(EntityManagerInterface $entity ,Request $request,SpecialiteRepository $specialiteRepository): Response
    { 
        $specialite=$specialiteRepository->findAll();
        return $this->render('patient/index.html.twig', [
            
        ]); 

        //return $this->json(['specialite' => $specialite]);
    }


    #[Route('/show/doctor', name: 'app_doctors_liste')]
    public function liste(EntityManagerInterface $entity ,Request $request,DoctorsRepository $doctors,SpecialiteRepository $specialiteRepository ): Response
    { 
        $liste=$doctors->findAll();
                   
        $specialiteliste=$specialiteRepository->findAll();
       // dd($liste);
        
        return $this->render('doctor/index.html.twig', [
            'doctor' => $liste,
            'specialite'=>$specialiteliste,
        
        ]);
    }
   
    #[Route('/doctor/add', name: 'app_dorrres',methods:['POST'])]
    public function add(ManagerRegistry $manager ,Request $request): Response
    {    try { $entity=$manager->getManager();
        
         $brochureFile = $request->files->get('file');
                
             $data =$request->request->all()  ;                                     
        

           // return $this->json(['nom'=> $request->request->all()['nom']]);
            $user=new Doctors();
            $user->setNom($data['nom']);
            //$user->setPrenom($data->prenom);
            $user->setPrenom($data['prenom']);
           // $user->setEmail($request->request->all()['nom']);
            $user->setEmail($data['email']);
            //$user->setDatenaissance($data->datenaissance);
            $user->setDatenaissance($data['datenaissance']);
           //  $user->setPays($dataata->pays);
            $user->setPays($data['pays']);
           // $user->setCapitale($dataata->capitale);
            $user->setCapitale($data['capitale']);
            $user->setAdresse($data['adresse']);
            $user->setTelephone($data['telephone']);
          $user->setGenre($data['genre']);
          $user->setSpecialite($data['specialite']);
  
          if (!empty($brochureFile)) {
              $source = fopen($brochureFile->imageCouverture, 'r');
              $nomUnique = md5(uniqid());
              $destination = fopen($this->getParameter('brochures_directory') . '/' . $nomUnique . '.png', 'w');
              stream_copy_to_stream($source, $destination);
              fclose($source);
              fclose($destination);
              // 1. write the http protocol
              $full_url = "http://";
              // 2. check if your server use HTTPS
              if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                  $full_url = "https://";
              }
              $lien =  $full_url . $_SERVER["HTTP_HOST"] . "/uploads/image/" . $nomUnique . '.png';
  
            //  $newProduitVariable->setImage($lien);
             $user->setImage($lien);
        
          }
          $entity->persist($user);
          $entity->flush();
          
          return $this->json(["code"=>"succes","message"=>"doctor ajourté avec success"]);
        
        } catch (\Exception $th) {
            
            return $this->json(["code"=>"erreur","message"=>$th]);
        }
         



}
     
    #[Route('{id}/show', name: 'app_doctortedds')]
    public function edit(EntityManagerInterface $entity,Request $request,DoctorsRepository $repository,$id): Response

    {   

        $user=$repository->find($id);

        if  (!$user) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $user->setNom($request->request->all()['nom']);
        //$user->setPrenom($data->prenom);
        $user->setPrenom($request->request->all()['prenom']);
       // $user->setEmail($request->request->all()['nom']);
        $user->setEmail($request->request->all()['email']);
        //$user->setDatenaissance($data->datenaissance);
        $user->setDatenaissance($request->request->all()['datenaissance']);
       //  $user->setPays($data->pays);
        $user->setPays($request->request->all()['pays']);
       // $user->setCapitale($data->capitale);
        $user->setCapitale($request->request->all()['capitale']);
        //$user->setAdresse($data->adresse);
        $user->setAdresse($request->request->all()['adresse']);
        // $user->setTelephone($data->telephone);

       // $user->setTelephone($data->telephone);
        $user->setTelephone($request->request->all()['telephone']);

     // $user->setGenre($request->request->all()['nom']);
        $user->setGenre($request->request->all()['genre']);
        $user->setSpecialite($request->request->all()['specialite']);
        $user->setImage('');
        $user->setFile('');
        $entity->flush();
        $response = new JsonResponse(['status' => "ok"]);
        return $response;

    }


    #[Route('/delete', name: 'app_deletedoctor')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $doctors=$entityManager->getRepository(Doctors::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($doctors);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }
    
    #[Route('/{id}/edit', name: 'app_doctoredit', methods: ['GET', 'POST'])]
    public function form(EntityManagerInterface $entityManager,SpecialiteRepository $specialiteRepository,DoctorsRepository $doctors,$id): Response
    {   // $id = (int) $request->request->get('id');
         $data=$doctors->findBy(['id' => $id]);
         $specialiteliste=$specialiteRepository->findAll(['id' => $id]);
         return $this->render('doctor/edit.html.twig', [
            'specialite'=>$specialiteliste,
           'data'=>$data[0]
           
            
        ]);
    }

    #[Route('/update/{id}', name: 'app_doctoreditimage', methods: ['GET', 'POST'])]

    public function upload_image(EntityManagerInterface $entityManager,Request $request,$id,DoctorsRepository $doctorsRepository): Response
    {

        //tous les elements qui transitent sur symfonny vient sur request ou data
        $brochureFile = $request->files->get('file');
        //$id = (int)$request->request->get('id');
       $user = $entityManager->getRepository(Doctors::class)->find($id);
      // $user= $doctorsRepository->find($id);
        if (!$user){
            return $this->json([
                'data' =>$user,
                'message' => 'le user n'.'existe pas',
                'id'=>$id
            ]);
        }
        if ($brochureFile) {
            //recuperer le nom original de l'image pathinfo=les informations d'un lien et on redefinit le nom
        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $originalFilename;
        //deviner l'extension du fichier
        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
        try {
            //recuperer l'imae et preciser le dossier de stockage
            $brochureFile->move(
                $this->getParameter('brochures_directory'),
                $newFilename
            );
            $user->setImage(
                //recuperer le chemin de l'image
                new File($this->getParameter('brochures_directory').'/'.$newFilename)
            );
            $entityManager->flush();
            return $this->json([
                'code' => 'succes',
                'message' => 'upload effectuée'
            ]);

        } catch (FileException $e) {
            return $this->json([
                'code' => 'error',
                'message' =>$e->getMessage()
            ]);
        } 
       
    }
       
    }



}




