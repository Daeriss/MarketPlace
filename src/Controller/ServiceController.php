<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function appointment(): Response
    {
        return $this->render('service/appointment.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
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
