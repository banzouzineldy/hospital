<?php

namespace App\Controller;

use App\Entity\Examen;
use App\Repository\ExamenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExamenController extends AbstractController
{
    #[Route('/examen', name: 'app_examen')]
    public function index(): Response
    {
        return $this->render('examen/index.html.twig', [
            'controller_name' => 'ExamenController',
        ]);
    }

    #[Route('/ajout', name: 'app_examen_ajout')]
    public function ajout(EntityManagerInterface $entitymanager,Request $request,$id,ExamenRepository $repository): Response
    {   $user=$entitymanager->getRepository(Examen::class)->findOneBy([
        "nom" =>$request->request->all()['nom'],
      
         ]); 

         if ($user == null){
            
            $user=new Examen();
            //$user->setNom($data->nom);
            $user->setNom($request->request->all()['nom']);
            $entitymanager->persist($user);
            $entitymanager->flush(); 
        
        }else{
            return $this->json([
                'code' =>1,
                'message' => 'cet utilisateur existe deja'
            ]);
        }

        return $this->json([
            'code' => 2,
            'message' => 'insertion effectuée'
        ]);
    

    }
    #[Route('/delete/examen', name: 'app_examen_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $examen=$entityManager->getRepository(Examen::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($examen);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }

        #[Route('/{id}/edit', name: 'app_examen_form', methods: ['GET', 'POST'])]
        public function form(EntityManagerInterface $entityManager,$id,ExamenRepository $examenrepository): Response
        {   // $id = (int) $request->request->get('id');
             $data=$examenrepository->findBy(['id' => $id]);
             //$specialiteliste=$specialiteRepository->findAll(['id' => $id]);
             return $this->render('examen/edit.html.twig', [
               // 'specialite'=>$specialiteliste,
               'data'=>$data[0]
               
                
            ]);
        }


        #[Route('/edit/{id}', name: 'app_examen')]
        public function edit(EntityManagerInterface $entity,Request $request,ExamenRepository $examenrepository,$id): Response
    
        { 
            $examen=$examenrepository->find($id);
    
            if  (!$examen) {
               
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }
            $examen->setNom($request->request->all()['nom']);
            $entity->flush();
            $response = new JsonResponse(['status' => "ok"]);
            return $response;
    
    
        }









}
