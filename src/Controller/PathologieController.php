<?php

namespace App\Controller;

use App\Entity\Pathologie;
use App\Entity\Specialite;
use App\Repository\PathologieRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PathologieController extends AbstractController
{
    #[Route('/pathologie', name: 'app_pathologie')]
    public function index(PathologieRepository $pathologierepository): Response
    {
        $listepathologie=$pathologierepository->findAll();
        return $this->render('pathologie/index.html.twig', [
            'pathologie'=> $listepathologie,
            'controller_name' => 'PathologieController',
        ]);
    }




    
    #[Route('/ajout/pathologie', name: 'app_pathologiead',methods:['POST'])]
    public function addpathologie(EntityManagerInterface $entity,Request $request): Response

    {   
         $data=json_decode($request->getContent());
            $pathologie=new Pathologie();
            $pathologie->setLibelle($data->libelle);
            $entity->persist($pathologie);
             $entity->flush();

        return $this->json(  [
            'code'=>1,
         'message'=>'insertion effectue'
        ]  
        );
    }


    #[Route('/form/pathologie', name: 'app_ajout/pathologie')]
public function form(PathologieRepository $pathologierepository,): Response
          
    {
        
        $listepathologie=$pathologierepository->findAll();
         return $this->render('pathologie/ajout.html.twig', [
            'pathologie' => $listepathologie,
            
        ]);

     
    }



      #[Route('/forms/pathologie/{id}', name: 'app_pathologies')]
        public function editid(PathologieRepository $pathologierepository,$id): Response
    
        {    $data=$pathologierepository->findBy(['id' => $id]);
            $listepathologie=$pathologierepository->findAll();
              return $this->render('pathologie/edit.html.twig', [
                'pathologie' => $listepathologie,
                'data' =>$data[0],
            ]);  
    
        
        }  
               



    #[Route('/{id}/edit/pathologie ', name: 'app_editpathologie',methods:['GET','POST'])]
 public function editpathologie(EntityManagerInterface $entity,Request $request,PathologieRepository $pathologierepository,$id): Response

 {
     $pathologie=$pathologierepository->find($id);

     if  (!$pathologie) {
        
         throw $this->createNotFoundException(
             'No product found for id '.$id
         );
     } 
     $pathologie->setLibelle($request->request->all()['libelle']);
     $entity->flush();
     return $this->json(  [
         'status'=>1,
      'message'=>'insertion effectue'
     ]  
     );
    
     
 }   

     #[Route('/deletepathologie', name: 'app_pathologie_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $pathologie=$entityManager->getRepository(Pathologie::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($pathologie);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }
        
}


