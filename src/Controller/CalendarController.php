<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use App\Repository\RdvRepository;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendare', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/ajout.html.twig', [
            
        ]);
    } 


    #[Route('/calendar', name: 'app_calendar_liste')]
    public function calendar(EntityManagerInterface $entityManager,Request $request,RendezvousRepository $calendar): Response
    {   
        $events=$calendar->findAll();
        $rdv=[];
        foreach ($events as $event) {
            $rdv[]=[
               /*  'id'=>$event->getId(),
                'date'=>$event->getDate(),
                 'heure'=>$event->getHeure(),
                 'patient'=>$event->getPatient(),
               */

                 'id'=>$event->getId(),
                'title'=>$event->getTitle(),
                'start'=>$event->getStart()->format('Y-m-d H:i'),
                'end'=>$event->getEnd()->format('Y-m-d H:i'),
                'patient'=>$event->getPatient(),
                 'motif'=>$event->getMotif(),
                 'background'=>$event->getBackground(),
                 'textcolor'=>$event->getTextcolor() 
     
            
             ]; 
        }
        $data=json_encode($rdv);
        return $this->render('calendar/index.html.twig',
         ['data'=>$data] 
        );
    }








    

}
