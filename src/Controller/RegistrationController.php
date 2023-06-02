<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserMiseajourType;
use App\Repository\SpecialiteRepository;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthentificatorAuthenticator $authenticator, EntityManagerInterface $entityManager,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger): Response
    {
        $user = new User();
        $specialites=$specialiteRepository->findAll();
        $specialitesfinal=[];

        foreach ($specialites as $key => $value) {
            $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
               
        }
        $form = $this->createForm(RegistrationFormType::class, $user,['specialites'=>$specialitesfinal]);
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
            $user->setSpecialite($specialite);
            $genre=$form->get('genre')->getData();
            $datenaissance=$form->get('datenaissance')->getData();

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
        ]);
    }
     #[Route('/user', name: 'app_user_liste')]
    public function index( UserRepository $userRepository): Response
    { 
        $user=$userRepository->findAll();
        return $this->render('registration/index.html.twig', [
           'user' =>$user,
    
        ]);
    }


    #[Route('/user/{id}', name: 'app_user_update',methods: [ 'POST','GET'])]
    public function  update(EntityManagerInterface $entityManager,Request $request,SpecialiteRepository $specialiteRepository,SluggerInterface $slugger,int $id): Response
    {   

        $user=$entityManager->getRepository(User::class)->find($id);
        $specialites=$specialiteRepository->findAll();
         $specialitesfinal=[];

         foreach ($specialites as $key => $value) {
             $specialitesfinal=array_merge( $specialitesfinal ,[$value->getNom()=>$value->getId()]);
                
         }
        $form =$this->createForm(UserMiseajourType::class,$user,['specialites'=>$specialitesfinal]);
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
            $user->setSpecialite($specialite);
            $genre=$form->get('genre')->getData();
            $datenaissance=$form->get('datenaissance')->getData();
            $entityManager->flush();
            return $this->redirectToRoute('app_user_liste');
          }
        return $this->render('registration/edit.html.twig', [
            'form' => $form->createView(),
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





}






































