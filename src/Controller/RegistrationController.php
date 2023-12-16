<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserMiseajourType;
use App\Form\UserMiseAjourType as FormUserMiseAjourType;
use App\Repository\FonctionRepository;
use App\Repository\QualificationRepository;
use App\Repository\ServiceRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\UniteRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use App\Security\UserAuthentificatorAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Contracts\Translation\TranslatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register_add')]
    public function register(Security $security ,Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthentificatorAuthenticator $authenticator, EntityManagerInterface $entityManager,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger,
    ServiceRepository $serviceRepository,UniteRepository $uniteRepository,
    QualificationRepository $qualificationRepository,FonctionRepository $fonctionRepository): Response
    {  
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comptes = $security->getUser();
        $users=$comptes;
        $user = new User();
        $specialites=$specialiteRepository->findAll();
        $services=$serviceRepository->findAll();
        $unites=$uniteRepository->findAll();
        $qualifications=$qualificationRepository->findAll();
        $fonctions=$fonctionRepository->findAll();
        $servicesfinal=[];
        $unitesfinal=[];
        $qualificationsfinal=[];
        $fonctionsfinal=[];
        $specialitesfinal=[];

        foreach ($services as $key => $value) {
            $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        foreach ($unites as $key => $value) {
           $unitesfinal=array_merge( $unitesfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       foreach ($qualifications as $key => $value) {
           $qualificationsfinal=array_merge( $qualificationsfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       foreach ($fonctions as $key => $value) {
           $fonctionsfinal=array_merge( $fonctionsfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }

        foreach ($specialites as $key => $value) {
            $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
               
        }
        
        $form = $this->createForm(RegistrationFormType::class, $user,['specialites'=>$specialitesfinal,
        'services'=>$servicesfinal,
        'fonctions'=>  $fonctionsfinal,
         'unites'=>$unitesfinal,
         'qualifications'=> $qualificationsfinal
       ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
           // $role= $form->get('roles')->getData();
          // $user->setRoles(["{$role}"]);
          $brochureFile = $form->get('brochure')->getData();
          if ($brochureFile) {
              $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
              // this is needed to safely include the file name as part of the URL
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

              // Move the file to the directory where brochures are stored
              try {
                  $brochureFile->move(
                      $this->getParameter('brochures_directory'),
                      $newFilename
                  );
              } catch (FileException $e) {
                  // ... handle exception if something happens during file upload
              }

              // updates the 'brochureFilename' property to store the PDF file name
              // instead of its contents
              $user->setImage($newFilename);
          }
            $specialiteid=$form->get('specialite')->getData();
            $specialite=$specialiteRepository->find($specialiteid);
            $genre=$form->get('genre')->getData();
            $datenaissance=$form->get('datenaissance')->getData();
            $serviceid=$form->get('service')->getData();
            $uniteid=$form->get('unite')->getData();
            $qualificationid=$form->get('qualification')->getData();
            $fonctionid=$form->get('fonction')->getData();
            $service=$serviceRepository->find($serviceid);
            $unite=$uniteRepository->find($uniteid);
            $qualification=$qualificationRepository->find($qualificationid);
            $fonction=$fonctionRepository->find($fonctionid); 
            $user->setSpecialite($specialite); 
            $user->setQualification($qualification);
            $user->setUnite($unite);
            $user->setService($service);

            $user->setFonction($fonction); 
            $users=$entityManager->getRepository(User::class)->findOneBy(['nom'=>$user->getNom(),
             'telephone'=>$user->getTelephone()
               ]);
             if ( $users!= null) {
                $this->addFlash('danger', 'cet utilisateur existe deja.'.$user->getNom().$user->getTelephone());  
              
            }else {
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_user_liste');
                
            }   
           
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user'=>$users
        ]);
    }
     #[Route('/user', name: 'app_user_liste')]
    public function index( UserRepository $userRepository,Security $security): Response
    {   $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comptes = $security->getUser();
        $compte=$comptes;
        $user=$userRepository->findAll();
        return $this->render('registration/index.html.twig', [
           'user' =>$user,
           'users'=>$compte
    
        ]);
    }


    #[Route('/update/user/{id}', name: 'app_user_update',methods: [ 'POST','GET'])]
    public function  update(Security $security,EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger,int $id,ServiceRepository $serviceRepository,QualificationRepository $qualificationRepository,UniteRepository $uniteRepository,FonctionRepository $fonctionRepository): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comptes = $security->getUser();
        $users=$comptes;
        $user=$entityManager->getRepository(User::class)->find($id);
        $specialites=$specialiteRepository->findAll();
        $services=$serviceRepository->findAll();
        $unites=$uniteRepository->findAll();
        $qualifications=$qualificationRepository->findAll();
        $fonctions=$fonctionRepository->findAll();
        $servicesfinal=[];
        $unitesfinal=[];
        $qualificationsfinal=[];
        $fonctionsfinal=[];
        $specialitesfinal=[];
         $specialitesfinal=[];
         foreach ($services as $key => $value) {
            $servicesfinal=array_merge( $servicesfinal ,[$value->getLibelle()=>$value->getId()]);
               
        }
        foreach ($unites as $key => $value) {
           $unitesfinal=array_merge( $unitesfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       foreach ($qualifications as $key => $value) {
           $qualificationsfinal=array_merge( $qualificationsfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }
       foreach ($fonctions as $key => $value) {
           $fonctionsfinal=array_merge( $fonctionsfinal ,[$value->getLibelle()=>$value->getId()]);
              
       }

         foreach ($specialites as $key => $value) {
             $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
                
         }
        $form =$this->createForm(FormUserMiseAjourType::class,$user,['specialites'=>$specialitesfinal,
        'services'=>$servicesfinal,
        'fonctions'=>  $fonctionsfinal,
         'unites'=>$unitesfinal,
         'qualifications'=> $qualificationsfinal
     
      ]);
          $form->handleRequest($request);
    
          if ($form->isSubmitted() && $form->isValid()) { 
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                 $user->setImage($newFilename);
            }
            $specialiteid=$form->get('specialite')->getData();
             $specialite=$specialiteRepository->find($specialiteid);
             $serviceid=$form->get('service')->getData();
             $uniteid=$form->get('unite')->getData();
             $qualificationid=$form->get('qualification')->getData();
             $fonctionid=$form->get('fonction')->getData();
             $genre=$form->get('genre')->getData();
             $datenaissance=$form->get('datenaissance')->getData();
             $service=$serviceRepository->find($serviceid);
             $unite=$uniteRepository->find($uniteid);
             $qualification=$qualificationRepository->find($qualificationid);
             $fonction=$fonctionRepository->find($fonctionid);
            $user->setSpecialite($specialite);
            $user->setQualification($qualification);
            $user->setUnite($unite);
            $user->setService($service);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_liste');
          }
        return $this->render('registration/edit.html.twig', [
            'form' => $form->createView(),
            'user'=>$users
        ]);
    }


    #[Route('/supprimer/user', name: 'app_register_delete',methods:[ 'POST'])]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response

        {   $user=$entityManager->getRepository(User::class)->find(
            ['id'=>$request->request->get('id')]) ;
           $entityManager->remove($user);
           $entityManager->flush();
         
            return $this->json([
            'code'=>1,
             'message'=>'suppression effectué'
 
             ]);


        }


        #[Route('/user/{id}', name: 'app_user_unique',methods:[ 'GET'])]
        public function userunique(Security $security,EntityManagerInterface $entityManager,$id): Response
    
            {   $user=$entityManager->getRepository(User::class)->find($id);
                $comptes = $security->getUser();
                $compte=$comptes;
                
                return $this->render('registration/user.html.twig', [
                    'userid'=>$user,
                    'users'=>$compte
                ]);
    
            }





}






































