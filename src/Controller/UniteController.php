<?php

namespace App\Controller;

use App\Entity\Unite;
use App\Repository\UniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UniteController extends AbstractController
{
    #[Route('/unite', name: 'app_unite')]
    public function index(UniteRepository $uniterepository): Response
    {
        $listeunite=$uniterepository->findAll();
        return $this->render('unite/index.html.twig', [
            'unite'=> $listeunite,
            'controller_name' => 'UniteController',
        ]);
    }




    
    #[Route('/ajout/unite', name: 'app_unitead',methods:['POST'])]
    public function addunite(EntityManagerInterface $entity,Request $request): Response

    {   
         $data=json_decode($request->getContent());
            $unite=new Unite();
            $unite->setLibelle($data->libelle);
            $entity->persist($unite);
             $entity->flush();

        return $this->json(  [
            'code'=>1,
         'message'=>'insertion effectue'
        ]  
        );
    }


    #[Route('/form/unite', name: 'app_ajout/unite')]
public function form(UniteRepository $uniterepository,): Response
          
    {
        
        $listeunite=$uniterepository->findAll();
         return $this->render('unite/ajout.html.twig', [
            'unite' => $listeunite,
            
        ]);

     
    }



      #[Route('/forms/unite/{id}', name: 'app_unites')]
        public function editid(UniteRepository $uniterepository,$id): Response
    
        {    $data=$uniterepository->findBy(['id' => $id]);
            $listeunite=$uniterepository->findAll();
              return $this->render('unite/edit.html.twig', [
                'unite' => $listeunite,
                'data' =>$data[0],
            ]);  
    
        
        }  
               



    #[Route('/{id}/edit/unite', name: 'app_editunite',methods:['GET','POST'])]
 public function editunite(EntityManagerInterface $entity,Request $request,UniteRepository $uniterepository,$id): Response

 {
     $unite=$uniterepository->find($id);

     if  (!$unite) {
        
         throw $this->createNotFoundException(
             'No product found for id '.$id
         );
     } 
     $unite->setLibelle($request->request->all()['libelle']);
     $entity->flush();
     return $this->json(  [
         'status'=>1,
      'message'=>'insertion effectue'
     ]  
     );
    
     
 }   

     #[Route('/deleteunite', name: 'app_unite_delete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $unite=$entityManager->getRepository(Unite::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($unite);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        } 
}
