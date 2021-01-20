<?php

namespace App\Controller;

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
    public function accueilshopkeeper()
    {
        return $this->render('shop_keeper/indexshopKeeper.html.twig');
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

        $listecommande = $orderRepository->findBy(
            ['shop' => $shop],
            []
        );

        return $this->render('shop_keeper/shopkeeperorders.html.twig', [
            'orders' => $listecommande
        ]);
    }
}
