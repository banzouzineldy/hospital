<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Entity\Plagehoraire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PlagehoraireRepository;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlagehoraireController extends AbstractController
{ 

   #[Route('/plagehoraire', name: 'app_plage_horaire', methods: ['GET'])]
    public function liste(EntityManagerInterface $entityManager,PlagehoraireRepository $plagehoraireRepository,Security $security): Response
      
    {
        //on verifie si l'utilisateur est connectée
    
    
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }else   
            $comptes = $security->getUser();
            // Récupérez le login de l'utilisateur connecté
            $login = $comptes->getUserIdentifier();
           // $entityManager = $doctrine->getManager();
            //on recupere tous les evenements de la base de données
            $plage_horaire =  $plagehoraireRepository->findAll();
             $records = $plagehoraireRepository->findBy(['utilisateur' =>$login]);
            $events           = []; // tableau pour stocker les événements
            foreach ($records as $result) {
                $event = [
                    'id' => $result->getId(),
                    'title' => $result->getTitle(),
                    'start' => $result->getStart(),
                    'end' => $result->getEnd(),
                    'description' => $result->getDescription(),
                    
                    //'url' => $this->generateUrl('app_plage_horaire', ['id' => $result->getId()])
                    ];
                $events[] = $event;
            }
       
            $jsonEvents = json_encode($events);
           // dd($jsonEvents);
            return $this->render('plagehoraire/index.html.twig', [
                'jsonEvents'    => $jsonEvents,
                   
            ]);  
    }


    #[Route('/plagehoraire/add', name: 'app_plagehoraire_add',methods:['POST'])]
      
    public function add(EntityManagerInterface $entityManager,Request $request,Security $security): Response
    {
           
            try { 
               //

                    $user=$security->getUser(); 
                  // $user=$entityManager->getRepository(Doctors::class)->find($data->idDoctors);
                    $plage=new Plagehoraire();
                    $plage->setTitle($request->request->all()['title']);
                    $plage->setStart(date($request->request->all()['start']));
                    $plage->setEnd(date($request->request->all()['end']));
                    $plage->setUtilisateur($user->getUserIdentifier()); 
                    $plage->setDescription($request->request->all()['description']);
                    $plage->setUtilisateurs($user);
                    $entityManager->persist($plage);
                    $entityManager->flush();  
                     return $this->json(['code'=>1]);
                 
            } catch (PDOException $e) {
                return $this->json([
                    'code'=>1,
                   'message'=>$e,
                  
                 ] ); 
            }
           
        

    }

    #[Route('/{id}/edit/plage', name: 'app_horaire_edit_plage',methods: [ 'POST'])]
    public function edit(EntityManagerInterface $entity,Request $request,PlagehoraireRepository $repository,$id,Security $security): Response

    {   
          $plage=$repository->find($id);

        if  (! $plage) {
           
            throw $this->createNotFoundException(
                'il nya pas cet id '.$id
            );
        }
        $user=$security->getUser(); 
        $data=$request->request->all();
        $plage->setTitle($data['title']);
        $plage->setStart(date($request->request->all()['start']));
        $plage->setEnd(date($request->request->all()['end'])); 
        $plage->setUtilisateurs($user);
          $entity->flush();
        return $this->json([
            'code'=>1,
             'message'=>'Mise a jour effectué'
    
             ]);

    }

  
    #[Route('/delete/plagehoraire/{id}', name: 'app_delete_plage_horaire',methods:['POST'])]
 public function delete(EntityManagerInterface $entityManager,Request $request): Response
           
     {  
         $plagehoraire=$entityManager->getRepository(Plagehoraire::class)->find(
        ['id'=>$request->request->get('id')]) ;
        $entityManager->remove($plagehoraire);
        $entityManager->flush();
     
        return $this->json([
        'code'=>1,
         'message'=>'suppression effectué'

         ]);
     
     }


     #[Route('/plage/horaire/new', name: 'app_plage_horaire_new', methods: ['GET', 'POST'])]
     public function addEvent( Request $request,ManagerRegistry $doctrine, 
     PlageHoraireRepository $PlageHoraireRepository, PlageHoraire $PlageHoraire): Response
     {
         
         if (!empty($request->request->all()) && $request->request->all()['titre']) {
             
             $entityManager = $doctrine->getManager();
             $user = $this->getUser();
             $plage_horaire = new PlageHoraire();
             $plage_horaire->setStart(date($request->request->all()['start']));
             $plage_horaire->setEnd(date($request->request->all()['end']));
             $plage_horaire->setTitle($request->request->all()['title']);
             $plage_horaire->setUtilisateur($user->email);
             $plage_horaire->setUtilisateurs($user);
             $entityManager->persist($plage_horaire);
             $entityManager->flush();
             $entityManager = $doctrine->getManager();
             $response = new JsonResponse(['status' => "ok"]);
             return $response;
         }  
         $plage_horaire = $PlageHoraireRepository->findAll();
         
         return $this->render('plage_horaire/_form.html.twig', [
             'plage_horaire' => $plage_horaire,
             
         ]);
     }


    





}
