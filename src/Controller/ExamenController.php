<?php

namespace App\Controller;

use Exception;
use App\Entity\Examen;
use App\Form\ExamenType;
use App\Form\ExamenMiseAjourType;
use App\Repository\ExamenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExamenController extends AbstractController
{
    #[Route('/examen/ajout', name: 'app_examen_add')]
    public function index(Security $security, EntityManagerInterface $entityManager,ExamenRepository $examenRepository,Request $request): Response
    {
        $user=$security->getUser();
        $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       } 

        $examen=new Examen();
        $form=$this->createForm(ExamenType::class,$examen);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            try {
                $examens=$examenRepository->findOneBy(['libelle'=>$examen->getLibelle()]);
                if ( $examens!= null) {
                    $this->addFlash('danger', 'ce nom existe deja.');  
                  
                }else {
                    $entityManager->persist($examen);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_examen_liste');
                    
                }      
                              
            } catch (Exception $e) {
                //throw $th;
                dd($e->getMessage());
            }
           
        }
        return $this->render('examen/ajout.html.twig', [
            'form' => $form->createView(),
           
             
         ]);
        }

         #[Route('/edit/examen/{id}', name: 'app_examen_edit',methods:['GET','POST'])]
         public function edit(Security $security,EntityManagerInterface $entityManager,Request $request,ExamenRepository $examenRepository,$id): Response
     
         {     $user=$security->getUser();
            $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
        if (!array_intersect($user->getRoles(), $roles)) {
            throw new AccessDeniedException('Acces refuse');
        } 
             $examen=$examenRepository->find($id);
     
             $form=$this->createForm(ExamenMiseAjourType::class,$examen);
             $form->handleRequest($request);
             
             if ($form->isSubmitted() && $form->isValid()) { 
                 $specialite = $form->getData();
                 $entityManager->flush();
                 $this->addFlash('success', 'la modification a reussi'); 
                 return $this->redirectToRoute('app_examen_liste'); 
           
             }
             return $this->render('examen/edit.html.twig', [
                 'form' => $form->createView(),
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
            'message'=>'suppresion effectué'

            ]);
        
        }


        #[Route('/examen', name: 'app_examen_liste')]
        public function liste(Security $security,ExamenRepository $examenRepository): Response
                  
            {   if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {

                return new RedirectResponse($this->generateUrl('app_login'));
               
               }else {
                $user=$security->getUser();
               $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
              if (!array_intersect($user->getRoles(), $roles)) {
                throw new AccessDeniedException('Acces refuse');
              } 

                $examen=$examenRepository->findAll();

              return $this->render('examen/index.html.twig', [
                'examens'=>$examen,
                'user'=>$user
            ]);
             
        }

                  
    }


   


       


    






}
