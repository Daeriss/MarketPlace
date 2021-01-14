<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DistrictType;
use Symfony\Component\HttpFoundation\Request;

class MarketController extends AbstractController
{
    /**
     * @Route("/market", name="market")
     */
    public function index(): Response
    {
        return $this->render('market/index.html.twig', [
            'controller_name' => 'MarketController',
        ]);
    }

    /**
     * @Route("/", name="accueil")
     */
    public function accueil(Request $request): Response
    {

        $form = $this->createForm(DistrictType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $district = $form->get('Code_Postale')->getData();
        
        dump($district);
        }
        return $this->render('market/index.html.twig', [
            'form' => $form->createView(),
            'district' => $district ?? "13001"
        ]);
    }

    /**
     * @Route("/shopkeeper/accueilshopkeeper", name="accueilshopkeeper")
     */
    public function accueilshopkeeper()
    {
        return $this->render('market/indexshopKeeper.html.twig');
    }
}
