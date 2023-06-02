<?php

namespace App\Controller;

use App\Repository\PatientRepository;
use App\Repository\RdvsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(EntityManagerInterface $entityManager,
    
     RdvsRepository $rdvsRepository,PatientRepository $patientRepository,UserRepository $userRepository): Response

    {  $rendez_Vous=$rdvsRepository->findAll();
         $chartData1 = array(); 

       $patients=$patientRepository->createQueryBuilder('p')
       ->select('COUNT(p.id)')
        ->getQuery()
        ->getSingleResult();

        $rendezVous=$rdvsRepository->createQueryBuilder('r')
        ->select('COUNT (r.id)')
        ->getQuery()
        ->getSingleResult();

        $totalutisateurs=$userRepository->createQueryBuilder('u')
         ->select('COUNT (u.id)')
         ->getQuery()
         ->getSingleResult();

        $data = [
            'patients' => count($patients),
            'rendez-Vous' => count($rendezVous),

        ];
        $jsonData = json_encode($data);


            return $this->render('administration/index.html.twig', [
                'jsonData'                   =>  $jsonData,
                'utilisateurs'               => $totalutisateurs,
                'rendezVous'                  =>$rendezVous,
                'patient'                     =>$patients,
               // 'chartDataAnnuelle'          =>  $chartDataAnnuelle,
            
            ]);
    }
}
