<?php

namespace App\Controller;

use App\Entity\Lit;
use App\Form\LitType;
use App\Form\LitMiseAJourType;
use App\Repository\LitRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LitController extends AbstractController
{
    #[Route('/lit', name: 'app_lit')]
    public function index(EntityManagerInterface $entityManager,LitRepository $litRepository): Response
    {
    
        $lits=$litRepository->findAll();
        return $this->render('lit/index.html.twig', [
            'controller_name' => 'LitController',
            'lit' =>$lits,
        ]);
    }


    
    #[Route('/lit/add', name: 'app_lit_add',methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request,ChambreRepository $chambreRepository,SluggerInterface $slugger): Response
    {   

        $lits=new Lit();
        $chambres=$chambreRepository->findAll();
         $chambresfinal=[];
        

         foreach ($chambres as $key => $value) {
             $chambresfinal=array_merge( $chambresfinal ,[$value->getNumChambre()=>$value->getId()]);
                
         }
        
        $form =$this->createForm(LitType::class,$lits,['chambres'=>$chambresfinal]);
       
        
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
           
            $membre = $entityManager->getRepository(Lit::class)->findOneBy([
                'numLit'=>$lits->getNumLit(),
             ]);
             if($membre != null){
                 dd('le numero existe deja');
 
             }

           
            $chambreid=$form->get('chambre')->getData();
             $chambre=$chambreRepository->find($chambreid);
            $lits->setChambre($chambre);
             $numLit=$form->get('numLit')->getData();
             $status=$form->get('status')->getData();
             $lits->setNumLit($numLit);
             $lits->setStatus($status);
            $entityManager->persist($lits);
            $entityManager->flush();
            return $this->redirectToRoute('app_lit');
          }
        return $this->render('lit/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/lit/{id}', name: 'app_lit_update',methods: [ 'POST','GET'])]
    public function  update(EntityManagerInterface $entityManager,Request $request,ChambreRepository $chambreRepository,SluggerInterface $slugger,int $id): Response
    {   

        $user=$entityManager->getRepository(Lit::class)->find($id);
        $chambres=$chambreRepository->findAll();
         $chambresfinal=[];

         foreach ($chambres as $key => $value) {
            $chambresfinal=array_merge( $chambresfinal ,[$value->getNumChambre()=>$value->getId()]);
               
        }
         
        $form =$this->createForm(LitMiseAJourType::class,$user,['chambres'=>$chambresfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            
            $chambreid=$form->get('chambre')->getData();
             $chambre=$chambreRepository->find($chambreid);
            $user->setChambre($chambre);
             $numLit=$form->get('numLit')->getData();
             $user->setNumLit($numLit);
            $entityManager->flush();
            return $this->redirectToRoute('app_lit');
          }
        return $this->render('lit/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/lit/{id}', name: 'app_lit_delete')]
    public function delete(EntityManagerInterface $entityManager,int $id,Request $request): Response
              


        {   $lit=$entityManager->getRepository(Lit::class)->find(
            ['id'=>$request->request->get('id')])
             ;
           /*   if (!$doctor) {
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            } */
           $entityManager->remove( $lit);
           $entityManager->flush();
         
            return $this->json([
            'code'=>1,
             'message'=>'suppression effectuée'
 
             ]);


        }
}
