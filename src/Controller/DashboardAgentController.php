<?php

namespace App\Controller;

use App\Repository\RdvsRepository;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DashboardAgentController extends AbstractController
{
    #[Route('/agent', name: 'app_dashboard_agent')]
    public function index(Security $security,RdvsRepository $rdvsRepository,PatientRepository $patientRepository,UserRepository $userRepository): Response
    
    {  
        $this->denyAccessUnlessGranted('ROLE_AGENT');
        // $this->denyAccessUnlessGranted('ROLE_MEDECIN');
         if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {

             return new RedirectResponse($this->generateUrl('app_login'));
            
            }else   
             $comptes = $security->getUser();
             $user = $comptes;
             // Récupérez le login de l'utilisateur connecté
                $login = $comptes->getUserIdentifier(); 
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
                
                    return $this->render('dashboard_agent/index.html.twig', [
                        'jsonData'                   =>  $jsonData,
                        'utilisateurs'               => $totalutisateurs,
                        'rendezVous'                  =>$rendezVous,
                        'patient'                     =>$patients,
                        'chartDataAnnuelle'          =>$chartDataAnnuelle,
                        'user'                       =>$user
                    
                    ]);
     }
        
       
        
        


}
