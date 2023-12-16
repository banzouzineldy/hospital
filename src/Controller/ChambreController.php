<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Form\ChambreMiseAJourType;
use App\Repository\UniteRepository;
use App\Repository\ChambreRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_chambre')]
    public function index(Security $security,EntityManagerInterface $entityManager,ChambreRepository $chambreRepository): Response
    {    $user=$security->getUser();
        $comptes=$user;
        $roles=['ROLE_ADMIN'];
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       }
        $specialitefinal=[];
        $chambres=$chambreRepository->findAll();
        return $this->render('chambre/index.html.twig', [
            'controller_name' => 'ChambreController',
            'chambre' =>$chambres,
            'user'=>$comptes
        ]);
    }


    
    #[Route('/chambre/add', name: 'app_chambre_add',methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $entityManager,Request $request,ServiceRepository $serviceRepository,UniteRepository $uniteRepository,SluggerInterface $slugger): Response
    {   

        $chambres=new Chambre();
        $services=$serviceRepository->findAll();
        $unites=$uniteRepository->findAll();
         $servicesfinal=[];
         $unitesfinal=[];
        

         foreach ($services as $key => $value) {
             $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
                
         }
         foreach ($unites as $key => $value) {
            $unitesfinal=array_merge( $unitesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        
        $form =$this->createForm(ChambreType::class,$chambres,['services'=>$servicesfinal,'unites'=>$unitesfinal]);
       
        
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
           
            $membre = $entityManager->getRepository(Chambre::class)->findOneBy([
                'numChambre'=>$chambres->getNumChambre(),
             ]);
             if($membre != null){
                 dd('le numero existe deja');
 
             }

           
            $serviceid=$form->get('service')->getData();
            $uniteid=$form->get('unite')->getData();
             $service=$serviceRepository->find($serviceid);
             $unite=$uniteRepository->find($uniteid);
            $chambres->setService($service);
            $chambres->setUnite($unite);
            
             $niveau=$form->get('niveau')->getData();
             $description=$form->get('description')->getData();
             $categorie=$form->get('categorie')->getData();
             $status=$form->get('status')->getData();
             $chambres->setNiveau($niveau);
             $chambres->setDescription($description);
             $chambres->setCategorie($categorie);
             $chambres->setStatus($status);
            $entityManager->persist($chambres);
            $entityManager->flush();
            return $this->redirectToRoute('app_chambre');
          }
        return $this->render('chambre/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/chambre/{id}', name: 'app_chambre_update',methods: [ 'POST','GET'])]
    public function  update(Security $security,EntityManagerInterface $entityManager,Request $request,ServiceRepository $serviceRepository,UniteRepository $uniteRepository,SluggerInterface $slugger,int $id): Response
    {      $users=$security->getUser();
          $comptes=$users;

        $user=$entityManager->getRepository(Chambre::class)->find($id);
        $services=$serviceRepository->findAll();
        $unites=$uniteRepository->findAll();
        $servicesfinal=[];
         $unitesfinal=[];
         
         foreach ($services as $key => $value) {
            $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        foreach ($unites as $key => $value) {
           $unitesfinal=array_merge( $unitesfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       
        $form =$this->createForm(ChambreMiseAJourType::class,$user,['services'=>$servicesfinal,'unites'=>$unitesfinal]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            
            $serviceid=$form->get('service')->getData();
            $uniteid=$form->get('unite')->getData();
            $service=$serviceRepository->find($serviceid);
             $unite=$uniteRepository->find($uniteid);
            $user->setService($service);
            $user->setUnite($unite);
            $niveau=$form->get('niveau')->getData();
            $description=$form->get('description')->getData();
            $categorie=$form->get('categorie')->getData();
             $status=$form->get('status')->getData();
             $user->setNiveau($niveau);
             $user->setDescription($description);
             $user->setCategorie($categorie);
             $user->setStatus($status);
            $entityManager->flush();
            return $this->redirectToRoute('app_chambre');
          }
        return $this->render('chambre/edit.html.twig', [
            'form' => $form->createView(),
            'user'=>$comptes
        ]);
    }

    #[Route('/delete/chambre/{id}', name: 'app_chambre_delete')]
    public function delete(EntityManagerInterface $entityManager,int $id,Request $request): Response
              


        {   $chambre=$entityManager->getRepository(Chambre::class)->find(
            ['id'=>$request->request->get('id')])
             ;
           /*   if (!$doctor) {
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            } */
           $entityManager->remove( $chambre);
           $entityManager->flush();
         
            return $this->json([
            'code'=>1,
             'message'=>'suppression effectuée'
 
             ]);


        }
}
