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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DashboardMedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_dashboard_medecin')]
    public function index(EntityManagerInterface $entityManagerInterface,Security $security,RdvsRepository $rdvsRepository,PatientRepository $patientRepository,UserRepository $userRepository ): Response

    {    $user=$this->getUser();
        $roles=['ROLE_MEDECIN','ROLE_ADMIN'];
    
       // $roles=$user->getRoles();
       if (!array_intersect($user->getRoles(), $roles)) {
         throw new AccessDeniedException('Acces refuse');
       }

        //$this->denyAccessUnlessGranted('ROLE_MEDECIN');
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }else   
            $comptes = $security->getUser();
            // Récupérez le login de l'utilisateur connecté
            $user=$comptes;
            $login = $comptes->getUserIdentifier();
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
                $years = $d->getDateautomatique()->format('Y-m-d');
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
           // $sql=$entityManagerInterface->getConnection();
            $countesdates= $rdvsRepository->createQueryBuilder('r')
             ->select('COUNT(r.dateautomatique)')
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

        return $this->render('dashboard_medecin/index.html.twig', [

            'jsonData'                   =>  $jsonData,
            'utilisateurs'               => $totalutisateurs,
            'rendezVous'                  =>$countesdates,
            'patient'                     =>$patients,
            'chartDataAnnuelle'          =>$chartDataAnnuelle,
            'user'                      =>$user
            
        ]);
    }
}
