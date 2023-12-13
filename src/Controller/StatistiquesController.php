<?php

namespace App\Controller;

use App\Repository\RdvsRepository;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatistiquesController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(EntityManagerInterface $entityManager,Security $security,
    
     RdvsRepository $rdvsRepository,PatientRepository $patientRepository,UserRepository $userRepository): Response

    {  $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comptes = $security->getUser();
        // Récupérez le login de l'utilisateur connecté
        $user=$comptes;
        $chartData1 = array(); 
        $chartData = array();
        $chartData1 = array();
        $chartData2 = array();
        $chartData3 = array();
        $chartData4 = array();
         $rendezVous=$rdvsRepository->findAll();
        $patients=$patientRepository->findAll();
        foreach ($patients as $d) {
            $year = $d->getDateEnregistrement()->format('Y-m-d');
            if (isset($chartData1[$year])) {
                $chartData1[$year] ++;
            }else {
                $chartData1[$year] = 1;
            }
        }
        foreach ($rendezVous as $d) {
            $years = $d->getDateEnregistrement()->format('Y-m-d');
            if (isset($chartData2[$years])) {
                $chartData2[$years] ++;
            }else {
                $chartData2[$years] = 1;
            }
        }
        $chartData = [
            'patients' =>$chartData1, 
           // 'deces' =>$chartData2, 
           // 'hospitalisations' =>$chartData3, 
            'rendez-Vous' =>$chartData2, 
        ];
        $chartDataAnnuelle = json_encode($chartData);
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

         $utisateurs=$userRepository->createQueryBuilder('u')
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
                'chartDataAnnuelle'          =>$chartDataAnnuelle,
                'user'                       =>$user
            
            ]);
    }
}
