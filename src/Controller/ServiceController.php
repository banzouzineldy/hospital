<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $servicerepository): Response
    {
        $listeservice=$servicerepository->findAll();
        return $this->render('service/index.html.twig', [
            'service'=> $listeservice,
            'controller_name' => 'ServiceController',
        ]);
    }




    
    #[Route('/ajout/service', name: 'app_servicead',methods:['POST'])]
    public function addservice(EntityManagerInterface $entity,Request $request): Response

    {   
         $data=json_decode($request->getContent());
            $service=new Service();
            $service->setLibelle($data->libelle);
            $entity->persist($service);
             $entity->flush();

        return $this->json(  [
            'code'=>1,
         'message'=>'insertion effectue'
        ]  
        );
    }


    #[Route('/form/service', name: 'app_ajout/service')]
public function form(ServiceRepository $servicerepository,): Response
          
    {
        
        $listeservice=$servicerepository->findAll();
         return $this->render('service/ajout.html.twig', [
            'service' => $listeservice,
            
        ]);

     
    }



      #[Route('/forms/service/{id}', name: 'app_services', methods: [ 'GET','POST'])]
        public function editid(ServiceRepository $servicerepository,$id): Response
    
        {    $data=$servicerepository->findBy(['id' => $id]);
            $listeservice=$servicerepository->findAll();
              return $this->render('service/edit.html.twig', [
                'service' => $listeservice,
                'data' =>$data[0],
            ]);  
    
        
        }  
               



    #[Route('/{id}/edit', name: 'app_editservice',methods:['POST'])]
 public function editservice(EntityManagerInterface $entity,Request $request,ServiceRepository $servicerepository,$id): Response

 {
     $service=$servicerepository->find($id);

     if  (!$service) {
        
         throw $this->createNotFoundException(
             'No product found for id '.$id
         );
     } 
     $service->setLibelle($request->request->all()['libelle']);
     $entity->flush();
     return $this->json(  [
         'status'=>1,
      'message'=>'insertion effectue'
     ]  
     );
    
     
 }   

     #[Route('/deleteservice', name: 'app_service_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $service=$entityManager->getRepository(Service::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($service);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }
        
    

}
