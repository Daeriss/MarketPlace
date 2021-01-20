<?php

namespace App\Controller;

use App\Form\HorairesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ShopRepository;

class ShopKeeperController extends AbstractController
{


    /**
     * @Route("/shopkeeper", name="accueilshopkeeper")
     */
    public function accueilshopkeeper(request $request): Response
    {
        $user = $this->getUser();
        $shop = $user->getShop();
        $form = $this->createForm(HorairesType::class, $shop);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('message', 'Horaires à jour');
        }
        return $this->render('shop_keeper/indexshopKeeper.html.twig', [
            'form' => $form->createView()
        ]);

    }
    
    /**
     * @Route("/orders/", name="shopkeeperorders", methods={"GET"})
     */
    public function shopkeeperorders(OrderRepository $orderRepository, Request $request)
    {

        $user = $this->getUser();
        $shop = $user->getShop();


        $request = Request::createFromGlobals();
        $request->query->get('statut');

        if ($request->query->get('statut') != null) {

           
            $order = $shop->getOrders()->getValues();
            $details = $order[0]->getOrderDetails();
            $details->setOrderStatus($request->query->get('statut'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($details);
            $entityManager->flush();
        }

        $listecommande = $orderRepository->findBy(
            ['shop' => $shop],
            []
        );

        return $this->render('shop_keeper/shopkeeperorders.html.twig', [
            'orders' => $listecommande
        ]);
    }
}
