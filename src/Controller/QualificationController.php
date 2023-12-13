<?php

namespace App\Controller;



use App\Entity\Qualification;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QualificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QualificationController extends AbstractController
{
    #[Route('/qualification', name: 'app_qualification')]
    public function index(QualificationRepository $qualificationrepository): Response
    {
        $listequalification=$qualificationrepository->findAll();
        return $this->render('qualification/index.html.twig', [
            'qualification'=> $listequalification,
            'controller_name' => 'QualificationController',
        ]);
    }




    
    #[Route('/ajout/qualification', name: 'app_qualificationad',methods:['POST'])]
    public function addqualification(EntityManagerInterface $entity,Request $request): Response

    {   
         $data=json_decode($request->getContent());
            $qualification=new Qualification();
            $qualification->setLibelle($data->libelle);
            $entity->persist($qualification);
             $entity->flush();

        return $this->json(  [
            'code'=>1,
         'message'=>'insertion effectue'
        ]  
        );
    }


    #[Route('/form/qualification', name: 'app_ajout/qualification')]
public function form(QualificationRepository $qualificationrepository,): Response
          
    {
        
        $listequalification=$qualificationrepository->findAll();
         return $this->render('qualification/ajout.html.twig', [
            'qualification' => $listequalification,
            
        ]);

     
    }



      #[Route('/forms/qualification/{id}', name: 'app_qualifications')]
        public function editid(QualificationRepository $qualificationrepository,$id): Response
    
        {    $data=$qualificationrepository->findBy(['id' => $id]);
            $listequalification=$qualificationrepository->findAll();
              return $this->render('qualification/edit.html.twig', [
                'qualification' => $listequalification,
                'data' =>$data[0],
            ]);  
    
        
        }  
               



    #[Route('/{id}/edit/qualification', name: 'app_editqualification',methods:['GET','POST'])]
 public function editqualification(EntityManagerInterface $entity,Request $request,QualificationRepository $qualificationrepository,$id): Response

 {
     $qualification=$qualificationrepository->find($id);

     if  (!$qualification) {
        
         throw $this->createNotFoundException(
             'No product found for id '.$id
         );
     } 
     $qualification->setLibelle($request->request->all()['libelle']);
     $entity->flush();
     return $this->json(  [
         'status'=>1,
      'message'=>'insertion effectue'
     ]  
     );
    
     
 }   

     #[Route('/deletequalification', name: 'app_qualification_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $qualification=$entityManager->getRepository(Qualification::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($qualification);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        } 
    
}
