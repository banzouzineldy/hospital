<?php

namespace App\Controller;

use App\Entity\ActeMedical;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ActeMedicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActeMedicalController extends AbstractController
{
    #[Route('/acte/medical', name: 'app_acte_medical')]
    public function index(): Response
    {
        return $this->render('acte_medical/index.html.twig', [
            
        ]);
    }



    #[Route('/edit/{id}', name: 'app_acte_medicaledit')]
    public function edit(EntityManagerInterface $entity,Request $request,ActeMedicalRepository $acterepository,$id): Response

    { 
        $specialite=$acterepository->find($id);

        if  (!$specialite) {
           
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $specialite->setNom($request->request->all()['nom']);
        $entity->flush();
        $response = new JsonResponse(['status' => "ok"]);
        return $response;


    }


    #[Route('/{id}/edit', name: 'app_actemedical_form', methods: ['GET', 'POST'])]
    public function form(EntityManagerInterface $entityManager,$id,ActeMedicalRepository $actemedicalrepository): Response
    {   // $id = (int) $request->request->get('id');
         $data=$actemedicalrepository->findBy(['id' => $id]);
         //$specialiteliste=$specialiteRepository->findAll(['id' => $id]);
         return $this->render('acte_medical/edit.html.twig', [
           // 'specialite'=>$specialiteliste,
           'data'=>$data[0]
           
              
        ]);
    }



    #[Route('/delete/acte', name: 'app_examen_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $actemedical=$entityManager->getRepository(ActeMedical::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($actemedical);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }


        #[Route('/ajout/actemedical', name: 'app_acte_medical_ajout')]
        public function ajout(EntityManagerInterface $entity,Request $request): Response
    
        {    $user=$entity->getRepository(ActeMedical::class)->findOneBy([
            "nom" =>$request->request->all()['nom'],
          
             ]); 
    
             if ($user == null){
                
                $user=new ActeMedical();
                //$user->setNom($data->nom);
                $user->setNom($request->request->all()['nom']);
                $entity->persist($user);
                $entity->flush(); 
            
            }else{
                return $this->json([
                    'code' =>1,
                    'message' => 'cette specialite existe deja'
                ]);
            }
    
            return $this->json([
                'code' => 2,
                'message' => 'insertion effectuée'
            ]); 
           
        }



        #[Route('/ajout/acte', name: 'app_medical_add_acte')]
        public function ajoutform(EntityManagerInterface $entity ,Request $request,ActeMedicalRepository $actemedicalrepository): Response
        { 
            $actemedical=$actemedicalrepository->findAll();
            return $this->render('acte_medical/ajout.html.twig', [
               
            ]);
        }


        























    




}
