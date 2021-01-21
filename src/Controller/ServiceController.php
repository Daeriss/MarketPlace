<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CalendarRepository;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="accueilservice")
     */
    public function accueilservice(): Response
    {
        return $this->render('service/accueilservice.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    /**
     * @Route("/service/appointment", name="appointment")
     */
    public function appointment(CalendarRepository $calendarRepository): Response
    {
        $events = $calendarRepository->findAll();
        $rdvs = [];

        foreach ($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'allDay' => $event->getAllDay(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('service/appointment.html.twig', compact('data'));
    }

    /**
     * @Route("/service/prestations", name="prestations")
     */
    public function prestations(): Response
    {
        return $this->render('service/prestations.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
