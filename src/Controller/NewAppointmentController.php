<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Calendar;

class NewAppointmentController extends AbstractController
{
    // /**
    //  * @Route("/new/appointment/", name="new_appointment", methods={"PUT"})
    //  */
    // public function new_appointment(?Calendar $calendar, Request $request): Response
    // {

    //     $donnees = json_encode($request->getContent());
    //     dump($donnees);

    //     if(isset($donnees)){
    //        // $start = $donnees->start;
    //     }
        
    //     // $response = $this->forward('App\Controller\CalendarController::new', [
    //     //     'start'  => $start,
    //     // ]);

    //     return $response;
    // }
}
