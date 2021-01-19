<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/orders", name="shopkeeperorders")
     */
    public function shopkeeperorders(OrderRepository $orderRepository)
    {
        

        $user = $this->getUser();
        $shop = $user->getShop();

        $order = $shop->getOrders();

        dump($order);
       

        $listecommande = $orderRepository->findBy(
            ['shop' => $shop],
            []
        );

        dump($listecommande);

        return $this->render('shop_keeper/shopkeeperorders.html.twig', [
            'orders' => $listecommande]);
        
    }
}
