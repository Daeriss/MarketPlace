<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CgdvController extends AbstractController
{
    /**
     * @Route("/cgdv", name="cgdv")
     */
    public function index(): Response
    {
        return $this->render('cgdv/index.html.twig', [
            'controller_name' => 'CgdvController',
        ]);
    }
}
