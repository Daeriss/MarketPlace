<?php

namespace App\Controller;

use App\Form\HorairesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrderRepository;
use App\Repository\SubOrderRepository;
use App\Repository\UserRepository;
use App\Repository\ShopRepository;

class ShopKeeperController extends AbstractController
{


    /**
     * @Route("/shopkeeper", name="accueilshopkeeper")
     */
    public function accueilshopkeeper(request $request, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $shop = $user->getShop();
        $form = $this->createForm(HorairesType::class, $shop);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('message', 'Horaires à jour');

        };
        $orders = $shop->getOrders()->getValues();


        $listecommande = $orderRepository->findBy(
            ['shop' => $shop],
            []
        );
        return $this->render('shop_keeper/indexshopKeeper.html.twig', [
            'orders' => $listecommande,
            'form' => $form->createView()
        ]);


    }

    /**
     * @Route("/orders/", name="shopkeeperorders", methods={"GET"})
     */
    public function shopkeeperorders(OrderRepository $orderRepository, SubOrderRepository $subOrderRepository, Request $request)
    {

        $user = $this->getUser();
        $shop = $user->getShop();


        $request = Request::createFromGlobals();
        $request->query->get('statut');
        $requestid = Request::createFromGlobals();
        $orderid = $requestid->query->get('id');

        if ($request->query->get('statut') != null) {

            $orders = $shop->getOrders()->getValues();


            for ($i = 0; $i < count($orders); $i++) {

                $order = $orders[$i];
                $details = $order->getOrderDetails();
                $status = $details->getOrderStatus();
                dump($orders[$i]);

                if ($orderid == $order->getId()) {

                    if ($status != "Récupéré") {

                        if ($request->query->get('statut') == "Terminé") {

                            $details->setOrderStatus("Terminé");
                            $this->getDoctrine()->getManager()->flush();
                        }

                        if ($request->query->get('statut') == "Récupéré") {

                            $details->setOrderStatus("Récupéré");
                            $this->getDoctrine()->getManager()->flush();
                        }
                    }
                }
            }
        }
        $orders = $shop->getOrders()->getValues();


        $listecommande = $orderRepository->findBy(
            ['shop' => $shop],
            []
        );
        $tablisteproduit = [];
        for ($i = 0; $i < count($orders); $i++) {

            $details = $orders[$i]->getOrderDetails();
            $tablisteproduit[$i] = $listeproduit = $subOrderRepository->findBy(
                ['orderDetails' => $details],
                []
            );
        }

        return $this->render('shop_keeper/shopkeeperorders.html.twig', [
            'orders' => $listecommande,
            'tablisteproducts' => $tablisteproduit
        ]);
    }
}
