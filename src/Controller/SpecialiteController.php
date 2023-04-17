<?php

namespace App\Controller;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpecialiteController extends AbstractController
{
    #[Route('/specialite', name: 'app_specialite')]
    public function ajout(EntityManagerInterface $entity,Request $request): Response

    {    $user=$entity->getRepository(Specialite::class)->findOneBy([
        "nom" =>$request->request->all()['nom'],
      
         ]); 

         if ($user == null){
            
            $user=new Specialite();
            //$user->setNom($data->nom);
            $user->setNom($request->request->all()['nom']);
            $entity->persist($user);
            $entity->flush(); 
        
        }else{
            return $this->json([
                'code' =>1,
                'message' => 'cette specialite existe deja'
            ]);
        }

        return $this->json([
            'code' => 2,
            'message' => 'insertion effectuée'
        ]); 
       
    }

    #[Route('/delete/specialite', name: 'app_specialitedelete')]
    public function delete(EntityManagerInterface $entityManager,Request $request): Response
              
        {   
            $examen=$entityManager->getRepository(Specialite::class)->find(
           ['id'=>$request->request->get('id')]) ;
          $entityManager->remove($examen);
          $entityManager->flush();
        
           return $this->json([
           'code'=>1,
            'message'=>'Mise a jour effectué'

            ]);
        
        }


        #[Route('/{id}/edit', name: 'app_specialiteform', methods: ['GET', 'POST'])]
        public function form(EntityManagerInterface $entityManager,$id,SpecialiteRepository $specialiteRepository): Response
        {   // $id = (int) $request->request->get('id');
             $data=$specialiteRepository->findBy(['id' => $id]);
             //$specialiteliste=$specialiteRepository->findAll(['id' => $id]);
             return $this->render('specialite/edit.html.twig', [
               // 'specialite'=>$specialiteliste,
               'data'=>$data[0]
               
                
            ]);
        }


        #[Route('/edit/{id}', name: 'app_specialiteedit')]
        public function edit(EntityManagerInterface $entity,Request $request,SpecialiteRepository $repository,$id): Response
    
        { 
            $specialite=$repository->find($id);
    
            if  (!$specialite) {
               
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }
            $specialite->setNom($request->request->all()['nom']);
            $entity->flush();
            $response = new JsonResponse(['status' => "ok"]);
            return $response;
    
    
        }


        #[Route('/liste', name: 'app_specialiteliste')]
        public function index(EntityManagerInterface $entity ,Request $request,SpecialiteRepository $specialiteRepository): Response
        { 
            $specialite=$specialiteRepository->findAll();
            return $this->render('specialite/index.html.twig', [
                'specialite' => $specialite,
            ]);
        }



        #[Route('/ajout/specialite', name: 'app_specialitelisteform')]
        public function ajoutform(EntityManagerInterface $entity ,Request $request,SpecialiteRepository $specialiteRepository): Response
        { 
            $specialite=$specialiteRepository->findAll();
            return $this->render('specialite/ajout.html.twig', [
               
            ]);
        }


        /* namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginFormType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
  /*   public function login(Request $request)
    {
        $form = $this->createForm(LoginFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // vérifiez les informations d'identification de l'utilisateur et récupérez le rôle
            $data = $form->getData();
            $username = $data['username'];
            $password = $data['password'];

            $user = // récupérez l'utilisateur à partir de la base de données en fonction du nom d'utilisateur et du mot de passe

            if (!$user) {
                // si l'utilisateur n'existe pas, afficher un message d'erreur
                $this->addFlash('danger', 'Nom d\'utilisateur ou mot de passe incorrect.');

                return $this->redirectToRoute('app_login');
            }

            // si l'utilisateur existe, connectez-le et redirigez-le vers la page d'accueil
            $this->get('security.token_storage')->setToken(new UsernamePasswordToken($user, null, 'main', $user->getRoles()));
            $this->get('session')->set('_security_main', serialize($this->get('security.token_storage')->getToken()));

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    } */
}








 

    

