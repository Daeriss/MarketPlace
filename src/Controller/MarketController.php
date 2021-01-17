<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DistrictType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\InputBag;
use App\Repository\ShopRepository;
use App\Entity\Shop;
use App\Entity\Product;
use App\Entity\Order;
use App\Form\CartValidatorType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class MarketController extends AbstractController
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

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
        $this->session->set('district', $district);
        dump($district);
        return $this->redirectToRoute('shops');

        }
        return $this->render('market/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/shopkeeper/accueilshopkeeper", name="accueilshopkeeper")
     */
    public function accueilshopkeeper()
    {
        return $this->render('market/indexshopKeeper.html.twig');
    }

    /**
     * @Route("/shops", name="shops")
     */
    public function shops(ShopRepository $shopRepository)
    {
        $listeShops = $shopRepository->findBy(
            ['adress' => $this->session->get('district')],
            []
        );
        
        return $this->render("market/shops.html.twig", [ 
            'shops' => $listeShops]);
    }

    /**
     * @Route("/shops/{id}", name="shop", methods={"GET"})
     */
    public function shop(Shop $shop, ProductRepository $productRepository): Response
    {
        $idShop = $shop->getId();
        $listeProducts = $productRepository->findBy(
            ['shop' => $shop],
            []
        );
        dump($listeProducts);
        return $this->render('market/shop.html.twig', [
            'shop' => $shop,
            'products' => $listeProducts
        ]);
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cart(): Response
    {
        return $this->render('market/cart.html.twig') ;
    }

    /**
     * @Route("/cartValidator", name="cartValidator")
     */
    public function cartValidator(Request $request, OrderRepository $orderRepository)
    {
        if($request->isXmlHttpRequest()){

            $panier = json_decode($request->request->get('a'));
            dump($panier);
            return new JsonResponse(json_decode($request->request->get('a')));

        } 

        dump($request->isXmlHttpRequest());
        
        
        // $delimiter = '^';
        // $panier = explode($delimiter, $_POST['postArray']);

        $order = new Order();

        $form = $this->createForm(CartValidatorType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        

        if ( $user != null) {

            if ($form->isSubmitted() && $form->isValid()) {

                $order->setorderNumber(rand(0, 100));
                $order->setDate(new \DateTime());
                $order->setUser($user);
            }
        
            return $this->render('market/cartValidator.html.twig', [
                'form' => $form->createView(),
                ]);
            }

        else {
            return $this->redirectToRoute('app_login');
        }
    }
}
