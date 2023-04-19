<?php

namespace App\Controller;

use App\Entity\Plagehoraire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PlagehoraireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlagehoraireController extends AbstractController
{
    #[Route('/plagehoraire', name: 'app_plagehoraire_liste')]
    public function index(): Response
    {
        return $this->render('plagehoraire/index.html.twig', [
           
        ]);
    }



    #[Route('/plage/horaire', name: 'app_plage_horaire', methods: ['GET'])]
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
            $records = $plagehoraireRepository->findBy(['utilisateur' => $login]);
            $events           = []; // tableau pour stocker les événements
            foreach ($records as $result) {
                $event = [
                    'id' => $result->getId(),
                    'title' => $result->getTitle(),
    
                    'start' => $result->getStart()->format('Y-m-d H:i:s'),
                    'end' => $result->getEnd()->format('Y-m-d H:i:s'),
                    
                    //'url' => $this->generateUrl('app_plage_horaire', ['id' => $result->getId()])
                    ];
                $events[] = $event;
            }
       
            $jsonEvents = json_encode($events);
            //dd($jsonEvents);
            return $this->render('plage_horaire/index.html.twig', [
                'jsonEvents'    => $jsonEvents,
                'plage_horaire' => $plage_horaire,
                'records'       => $records,   
            ]);  
    }










    #[Route('/plagehoraire/add', name: 'app_plagehoraire_add',methods:['POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request): Response
    {
       if (!empty($request->request->all()) && $request->request->all()['start']) {
            $plage=new Plagehoraire();
            $data=$request->request->all();
            $plage->setTitle($data['title']);
            $plage->setStart(new \DateTime($data['start']));
            $plage->setEnd(new \DateTime($data['end']));
            $entityManager->persist($plage);
            $entityManager->flush();
            return $this->json(
                ['code'=>'succes',
                 'message'=>'insertion effectué']); 
       }

    }

    #[Route('/{id}/edit', name: 'app_horaire_edit')]
    public function edit(EntityManagerInterface $entity,Request $request,PlagehoraireRepository $repository,$id): Response

    {   
          $plage=$repository->find($id);

        if  (! $plage) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $data=$request->request->all();
        $plage->setTitle($data['title']);
        $plage->setStart(new \DateTime($data['start']));
        $plage->setEnd(new \DateTime($data['end']));
       
          $entity->flush();
        return $this->json([
            'code'=>1,
             'message'=>'Mise a jour effectué'
    
             ]);

    }

    #[Route('/{id}/plage/edit', name: 'app_rendez_vousliste')]
    public function forme(EntityManagerInterface $entityManager,PlagehoraireRepository $rdv,$id): Response
    {  
        $data=$rdv->findBy(['id' => $id]);

        return $this->render('plagehoraire/edit.html.twig', [
               'data'=>$data[0]
            
        ]);
        
    }

    
    #[Route('/delete', name: 'app_delete_plage_horaire')]
 public function delete(EntityManagerInterface $entityManager,Request $request): Response
           
     {  
         $rendezvous=$entityManager->getRepository(Plagehoraire::class)->find(
        ['id'=>$request->request->get('id')]) ;
        $entityManager->remove($rendezvous);
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
             $plage_horaire->setUtilisateur($user->mail);
             $plage_horaire->setStart(new \DateTime($request->request->all()['start']));
             $plage_horaire->setEnd(new \DateTime($request->request->all()['end']));
             $plage_horaire->setTitle($request->request->all()['title']);
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
