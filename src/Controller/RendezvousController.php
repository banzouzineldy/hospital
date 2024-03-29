<?php

namespace App\Controller;

use Exception;
use App\Entity\Rdvs;
use App\Entity\User;
use App\Form\RdvsType;
use App\Entity\Rendezvous;
use App\Form\RdvsMiseAjourMedecinType;
use App\Form\RdvsMiseAjourType;
use Symfony\Component\Mime\Email;
use App\Repository\RdvsRepository;
use App\Repository\UserRepository;
use App\Repository\DoctorsRepository;
use App\Repository\PatientRepository;
use App\Repository\RendezvousRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlagehoraireRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;

class RendezvousController extends AbstractController
{  
     #[Route('/rendezvous', name: 'app_rendezvous_ajout')]
   public function index(EntityManagerInterface $entityManager,Request $request,PatientRepository $patientRepository,UserRepository $userRepository,SpecialiteRepository $specialiteRepository,MailerInterface $mailer,Security $security): Response
   {    
    if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {

      return new RedirectResponse($this->generateUrl('app_login'));
     
     }else {
      $user=$security->getUser();
     $roles=['ROLE_AGENT','ROLE_ADMIN'];
    if (!array_intersect($user->getRoles(), $roles)) {
      throw new AccessDeniedException('Acces refuse');
    } 
//$this->denyAccessUnlessGranted('ROLE_AGENT',);
    $comptes  =  $user;
     $rdvs=new Rdvs();
     //$admin='neldynicha@gmail.com';
     $patients=$patientRepository->findAll();
     $users=$userRepository->findAll();
     $specialites=$specialiteRepository->findAll();
     $specialitesfinal=[];
     $specialitesfinal=array_merge($specialitesfinal,['selectionner un service'=>0]);
     $userfinal=[];
     $patientfinal=[];
     $patientfinal=array_merge($patientfinal,['selectionner un patient'=>0]);
     foreach ($users as $key => $value) {
        $userfinal =array_merge($userfinal,[$value->getNom().' ' .$value->getPrenom()=>$value->getEmail()]);
             
      }
       
      foreach ($specialites as $key => $value) {
        $specialitesfinal =array_merge($specialitesfinal,[$value->getNom()=>$value->getId()]);
           
    }
   // dd( $specialitesfinal);

    foreach ($patients as $key => $value) {
        $patientfinal =array_merge( $patientfinal,[$value->getNom().' ' .$value->getPrenom()=>$value->getId()]);
           
    }
     $form=$this->createForm(RdvsType::class,$rdvs,['patients'=> $patientfinal,'emailmedecins'=>$userfinal,'specialites'=>$specialitesfinal]);
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) { 
        
        try { $admin=$this->getParameter('app.admin.email');
                $patienteid=$form->get('patient')->getData();
                $medecinemail=$form->get('emailsmedecin')->getData();
                $specialiteid=$form->get('specialite')->getData();
                $useremail=$userRepository->find($medecinemail);
                $patient=$patientRepository->find($patienteid);
                $specialite=$specialiteRepository->find($specialiteid);
                $rdvs->setSpecialite($specialite);
                $rdvs->setPatient($patient);
                $entityManager->persist($rdvs);  
                $entityManager->flush();
                $email = (new Email())
                  ->from($admin)
                  ->to($medecinemail)
                  ->subject('vous avez un nouveau rendezvous venant de neldy')
                  ->text('c est mon application delhopital');
                $mailer->send($email);
                $this->addFlash(
                    'success',
                    'votre demande a ete envoyé avec succes'
                );
                return $this->redirectToRoute('app_rendezvous_liste');
            }
        catch (Exception $e) {
          dd($e->getMessage());
        }
     
  
       }
    
       return $this->render('rendezvous/ajout.html.twig', [
          'form'=>$form->createView(),
          'user'=>$comptes
           
           
       ]);
      
     }
     
   }
   // recuperer les agendas des agents

   #[Route('/formulaire/rendezvous', name: 'app_rendezvous_forme')]
   public function formrendezvous(PlagehoraireRepository $plagehoraireRepository): Response
   {  
   // $this->denyAccessUnlessGranted('ROLE_AGENT');
   $user=$this->getUser();
     $comptes  =  $user;
        $roles=['ROLE_AGENT','ROLE_ADMIN'];

      // $roles=$user->getRoles();
      if (!array_intersect($user->getRoles(), $roles)) {
        throw new AccessDeniedException('Acces refuse');
      } 
       $plagehoraire=$plagehoraireRepository->findAll();
       return $this->render('medecin/medecinplage.html.twig', [
            'plagehoraires'=>$plagehoraire,
            'comptes'=>$comptes
           
       ]);
   }
   // recuperer les doctors ,les patients et les rendezvous

   #[Route('/rendezvous/liste', name: 'app_rendezvous_liste')]

   public function liste(RdvsRepository $rdvsRepository,UserRepository $userRepository): Response
   {  //$this->denyAccessUnlessGranted('ROLE_AGENT');
        $user=$this->getUser();
        $roles=['ROLE_AGENT','ROLE_ADMIN'];

      // $roles=$user->getRoles();
      if (!array_intersect($user->getRoles(), $roles)) {
        throw new AccessDeniedException('Acces refuse');
      } 
      $comptes  =  $user;
       $rendezvouslistes=$rdvsRepository->findAll();
          //dd(  $rendezvouslistes); 
  
       $userliste=[];

       foreach ($rendezvouslistes as  $value) {
        $user=$userRepository->findOneBy(['email' =>$value->getEmailsmedecin()]);
        if (!in_array($user,$userliste) ) {
          array_push($userliste,$user);   
        } 
        } 
       return $this->render('rendezvous/index.html.twig', [
          'rendezvousS'=>$rendezvouslistes,
          'users'=>$userliste,
          'comptes'=>$comptes
           
           
       ]);
   }
// formulaire de modification

   #[Route('/rendezvous/modifif/{id}', name: 'app_rendezvous_edit',methods:['GET','POST'])]
   public function modification(EntityManagerInterface $entityManager,Request $request,PatientRepository $patientRepository,UserRepository $userRepository,SpecialiteRepository $specialiteRepository,RdvsRepository $rdvsRepository,$id,MailerInterface $mailer): Response
   {  //$this->denyAccessUnlessGranted('ROLE_AGENT');
    $user=$this->getUser();
    $roles=['ROLE_AGENT','ROLE_ADMIN'];

   // $roles=$user->getRoles();
   if (!array_intersect($user->getRoles(), $roles)) {
     throw new AccessDeniedException('Acces refuse');
   }
   $comptes  =  $user;
     $rdvs=$rdvsRepository->find($id);
     $patients=$patientRepository->findAll();
     $users=$userRepository->findAll();
     $specialites=$specialiteRepository->findAll();
     $specialitesfinal=[];
     $specialitesfinal=array_merge($specialitesfinal,['selectionner un service'=>0]);
     $userfinal=[];
     $patientfinal=[];
     $patientfinal=array_merge($patientfinal,['selectionner un patient'=>0]);
     foreach ($users as $key => $value) {
        $userfinal =array_merge($userfinal,[$value->getNom().' ' .$value->getPrenom()=>$value->getEmail()]);
             
      }
      foreach ($specialites as $key => $value) {
        $specialitesfinal =array_merge($specialitesfinal,[$value->getNom()=>$value->getId()]);
           
    }

    foreach ($patients as $key => $value) {
        $patientfinal =array_merge( $patientfinal,[$value->getNom().' ' .$value->getPrenom()=>$value->getId()]);
           
    }
     
     $form=$this->createForm(RdvsMiseAjourType::class,$rdvs,['patients'=> $patientfinal,'emailmedecins'=>$userfinal,'specialites'=>$specialitesfinal]);
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) { 


        try { $admin=$this->getParameter('app.admin.email');
          $patienteid=$form->get('patient')->getData();
          $medecinemail=$form->get('emailsmedecin')->getData();
          $specialiteid=$form->get('specialite')->getData();
          $useremail=$userRepository->find($medecinemail);
          $patient=$patientRepository->find($patienteid);
          $specialite=$specialiteRepository->find($specialiteid);
          $rdvs->setSpecialite($specialite);
          $rdvs->setPatient($patient);
          $entityManager->persist($rdvs);  
          $entityManager->flush();
          $email = (new Email())
            ->from($admin)
            ->to($medecinemail)
            ->subject('votre rendez_vous  a ete modifie')
            ->text('c est mon application delhopital');
            $mailer->send($email);
            $this->addFlash(
              'success',
              'votre demande a ete envoyé avec succes'
          );
          return $this->redirectToRoute('app_rendezvous_liste');
      }
  catch (Exception $e) {
    dd($e->getMessage());
  }
         
   return $this->redirectToRoute('app_rendezvous_liste');
       
       }
    
       return $this->render('rendezvous/edit.html.twig', [
          'form'=>$form->createView(),
          'user'=>$comptes
              
       ]);
   }


   #[Route('/delete/rendezvous', name: 'app_rendezvous_delete',methods:['GET','POST'])]
   public function delete(EntityManagerInterface $entityManager,Request $request): Response
             
       {  // $this->denyAccessUnlessGranted('ROLE_AGENT');
         $rendezvous=$entityManager->getRepository(Rdvs::class)->find(
          ['id'=>$request->request->get('id')]) ;
         $entityManager->remove($rendezvous);
         $entityManager->flush();
       
          return $this->json([
          'code'=>1,
           'message'=>' Suppression  effectué'

           ]);
       
       }

       #[Route('/rendezvous/liste/medecin', name: 'app_rendezvous_medecin_liste')]
       public function list_medecin( Security $security, RdvsRepository $rdvsRepository,UserRepository $userRepository,EntityManagerInterface $entityManager): Response
       {  
        $user=$security->getUser();
        $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       } 
       if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
        return new RedirectResponse($this->generateUrl('app_login'));
    }
    else {
      $comptes   =     $security->getUser();
        $user     =      $comptes;
            // Récupérez le login de l'utilisateur connecté
        $login    =        $comptes->getUserIdentifier();
        $rendez_vous     =    $rdvsRepository->findAll();
        $rendezvouslistes = $rdvsRepository->findBy(['emailsmedecin' =>$login]);

           return $this->render('medecin/medecinrendezvous.html.twig', [
              'rendezvousS'=>$rendezvouslistes,
              'user'=>$user
                  
           ]);
       }
   

      }

       #[Route('/rendezvous/filtre', name: 'app_rendezvous_medecin_filtre',methods:['POST'])]
       public function filtre(SpecialiteRepository $specialiteRepository ,Request $request): Response
       { // $this->denyAccessUnlessGranted('ROLE_AGENT');
         $specialitefiltre=[];
          $data=json_decode($request->getContent(),false);
           if (empty($data->id)) {
            return $this->json(['code'=>'l id est introuvable']);
            
           }
          
           $userliste=$specialiteRepository->find(['id'=>$data->id]);
           //recuperation des utilisateurs
           $userfiltre=$userliste->getUser();

           //$utilisateursRoles=$userRepository->findAll();
           //dd($utilisatursRoles);
           $utilisateursfinal= [];
  
          foreach ($userfiltre as $value) {
            $roles=$value->getRoles();
  
            if (in_array("ROLE_MEDECIN",$roles)) {
              array_push( $utilisateursfinal,$value);
              
            }
            
          }

           foreach ( $utilisateursfinal as $key => $value) {
            array_push($specialitefiltre,['nom'=>$value->getNom().' ' .$value->getPrenom(),'id'=>$value->getEmail()]);
                 
          }
           return $this->json(['code'=>1,
           'utilisateur'=>$specialitefiltre]);
                     
           
       }


       #[Route('/rendezvous/filtre/modif', name: 'app_rendezvous_medecin_filtre_medif',methods:['POST'])]
       public function filtre_modif(SpecialiteRepository $specialiteRepository ,Request $request): Response
       { // $this->denyAccessUnlessGranted('ROLE_AGENT');
         $specialitefiltre=[];
          $data=json_decode($request->getContent(),false);
           if (empty($data->id)) {
            return $this->json(['code'=>'l id est introuvable']);
            
           }
          
           $userliste=$specialiteRepository->find(['id'=>$data->id]);
           $userfiltre=$userliste->getUser();
           $utilisateursfinal= [];
  
          foreach ($userfiltre as $value) {
            $roles=$value->getRoles();
  
            if (in_array("ROLE_MEDECIN",$roles)) {
              array_push( $utilisateursfinal,$value);
              
            }
            
          }

           foreach ( $utilisateursfinal as $key => $value) {
            array_push($specialitefiltre,['nom'=>$value->getNom().' ' .$value->getPrenom(),'id'=>$value->getEmail()]);
                 
          }
           return $this->json(['code'=>1,
           'utilisateur'=>$specialitefiltre]);
                     
           
       }
       
       
       #[Route('/rendezvous/liste/agent', name: 'app_rendezvous_liste_agent')]

   public function rendezMedecin(RdvsRepository $rdvsRepository,UserRepository $userRepository): Response
   {  //$this->denyAccessUnlessGranted('ROLE_AGENT');
        $user=$this->getUser();
        $roles=['ROLE_AGENT','ROLE_ADMIN'];

      // $roles=$user->getRoles();
      if (!array_intersect($user->getRoles(), $roles)) {
        throw new AccessDeniedException('Acces refuse');
      } 
      $comptes  =  $user;
       $rendezvouslistes=$rdvsRepository->findAll();
          
  
       $userliste=[];

       foreach ($rendezvouslistes as  $value) {
        $user=$userRepository->findOneBy(['email' =>$value->getEmailsmedecin()]);
        if (!in_array($user,$userliste) ) {
          array_push($userliste,$user);   
        } 
        } 
       return $this->render('dashboard_agent/rendezmedecin.html.twig', [
          'rendezvousS'=>$rendezvouslistes,
          'users'=>$userliste,
          'comptes'=>$comptes
           
           
       ]);
   }

       


     /*   #[Route('/rendezvous/role', name: 'app_rendezvous_medecin_liste')]
       public function list_role(UserRepository $userRepository): Response
       {  
         $utilisatursRoles=$userRepository->findBy(['roles'=>[
          "ROLE_MEDECIN"
        ]
         ]); 
         $utilisateursRoles=$userRepository->findAll();
         //dd($utilisatursRoles);
         $utilisateursfinal= [];

        foreach ($utilisateursRoles as $value) {
          $roles=$value->getRoles();

          if (in_array("ROLE_MEDECIN",$roles)) {
            array_push( $utilisateursfinal,$value);
            
          }
          
        }
        dd( $utilisateursfinal);
      
        return $this->render('nationalite/ajout.html.twig', [
          
          
           
           
       ]);

       } 

        */






}
