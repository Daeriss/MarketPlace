<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CalendarRepository;
use App\Entity\Calendar;
use DateTime;
use App\Entity\Services;
use App\Form\ServicesType;

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
     * @Route("/service/appointment", name="appointment", methods={"GET","POST"})
     */
    public function appointment(CalendarRepository $calendarRepository, Request $request): Response
    {
        $user = $this->getUser();
        $shop = $user->getShop();

        $events = $calendarRepository->findby(
            ['shop' => $shop],
            []
        );
        $rdvs = [];
        dump($events);

        foreach ($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'user_id' => $event->getUser(),
                'allDay' => $event->getAllDay(),
            ];
        }

        $data = json_encode($rdvs);
        
        // $user = $this->getUser();
        // $shop = $user->getShop();
        // $service = new Services();
        // $form = $this->createForm(ServicesType::class, $service);
        // $form->handleRequest($request);

        // if (in_array('ROLE_SERVICE', $user->getRoles())) {

        //     if ($form->isSubmitted() && $form->isValid()) {

        //         $service->setShop($shop);
        //         $entityManager = $this->getDoctrine()->getManager();
        //         $entityManager->persist($service);
        //         $entityManager->flush();
                
        //         return $this->redirectToRoute('services_index');
        //     }

        //     return $this->render('services/new.html.twig', [
        //         'service' => $service,
        //         'form' => $form->createView(),
        //         compact('data')
        //     ]);
        // }

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
