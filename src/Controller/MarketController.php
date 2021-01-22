<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// pour acceder aux entités
use App\Entity\Shop;
use App\Entity\Order;

// création et post-process des formulaires
use App\Form\DistrictType;
use App\Form\CartType;
use App\Entity\OrderDetails;
use App\Entity\SubOrder;
// accès aux colomnes des tables dans la BDD
use App\Repository\ShopRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ServicesRepository;
use App\Repository\CalendarRepository;


class MarketController extends AbstractController
{

    public function __construct(SessionInterface $session)
    {
        // création de la session
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

            return $this->redirectToRoute('shops');
        }
        return $this->render('market/index.html.twig', [
            'form' => $form->createView(),
        ]);
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
            'shops' => $listeShops
        ]);
    }

    /**
     * @Route("/shops/{id}", name="shop", methods={"GET"})
     */
    public function shop(Shop $shop, ProductRepository $productRepository, ServicesRepository $servicesRepository, CalendarRepository $calendarRepository): Response
    {

        $userShop = $shop->getUser();

        if (in_array('ROLE_SHOPKEEPER', $userShop->getRoles())) {
            $listeProducts = $productRepository->findBy(
                ['shop' => $shop],
                []
            );

            return $this->render('market/shop.html.twig', [
                'shop' => $shop,
                'products' => $listeProducts
            ]);
        }
        if (in_array('ROLE_SERVICE', $userShop->getRoles())) {

            

            $listeServices = $servicesRepository->findBy(
                ['shop' => $shop],
                []
            );
           
            $events = $calendarRepository->findby(
                ['shop' => $shop],
                []
            );
            
            $rdvs = [];
    
            foreach ($events as $event){
                $rdvs[] = [
                    'id' => $event->getId(),
                    'start' => $event->getStart()->format('Y-m-d H:i:s'),
                    'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                    'title' => $event->getTitle(),
                    'description' => $event->getDescription(),
                    'backgroundColor' => $event->getBackgroundColor(),
                    'allDay' => $event->getAllDay(),
                ];
            }
    
            $data = json_encode($rdvs);
           
            dump($data);

            return $this->render('market/shop.html.twig', [
                'shop' => $shop,
                'services' => $listeServices,
                'data' => compact('data')
            ]);
        } 

    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cart(Request $request,  OrderRepository $orderRepository,  ProductRepository $productRepository): Response
    {
        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);

        // on créer une ligne order et orderDetails
        $order = new Order();
        $orderdet = new OrderDetails();

        $user = $this->getUser();

        //si l'utilisateur est connecté
        if ($user != null) {

            if ($form->isSubmitted() && $form->isValid()) {

                // on récupère le tableau de la session qui a été transformer en string
                $panierString = $form->get('input')->getData();
                $delimiter = '^';
                $panier = explode($delimiter, $panierString); //et on recréer un tableau à partir de la  string

                // tous les produits dans le panier proviennent du même shop
                $idproduct = intval($panier[0]);
                // grâce à l'id d'un product on peut savoir de quel shop il s'agit
                $shopProduct = $productRepository->findOneBy(['id' => $idproduct]);
                $shop = $shopProduct->getShop();

                //on lie l'order et l'orderDetails
                $order->setOrderDetails($orderdet);
                $orderdet->setOrders($order);



                //on récupère l'order actuel
                $orderDetails = $order->getOrderDetails();
                $taillepanier = count($panier); //taille du tableau pour avoir accès au total qui est toujours la dernière cellule
                $collectDate = $form->get('collect_date')->getData(); // on récupère la date
                dump($panier);

                // pour affecter les produits on doit récuper les céllules qui contiennent la valeur de l'id du produit
                //le tableau est constité [id1],[quantité1],[id2][quandtité2].... l'id se trouve donc 1 case sur deux
                // ce quiexplique le $i+=2 on saute une case
                for ($i = 0; $i < $taillepanier; $i += 2) {

                    //si la case n'est pas vide 
                    if (isset($panier[$i]) && $panier[$i] != null && $panier[$i] != "") {

                        $subOrder = new SubOrder;
                        $orderdet->addSubOrder($subOrder);
                        $quantite = intval($panier[$i + 1]);
                        //on récupère le produit dans la bdd grace à l'id
                        $currentproduct = $productRepository->findOneBy(['id' => $panier[$i]]);
                        // et on l'ajoute dans la BDD
                        $subOrder->setQuantity($quantite);
                        $currentproduct->addSubOrder($subOrder);
                    }
                }

                $order->setCheckout(end($panier));
                $order->setorderNumber(rand(0, 100));
                $order->setDate(new \DateTime());
                $order->setUser($user);
                $order->setShop($shop);

                $orderDetails->setCollectDate($collectDate);
                $orderDetails->setOrderStatus("En Cours");


                //on ajoute le tout dans la BDD
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($order);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('cartValidator     '));
            }

            

            return $this->render('market/cart.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/cartValidator", name="cartValidator")
     */
    public function cartValidator(Request $request, OrderRepository $orderRepository)
    {

         $user = $this->getUser();
        // if($request->isXmlHttpRequest()){

        //     $panier = json_decode($request->request->get('a'));
        //     dump($panier);
        //     return new JsonResponse(json_decode($request->request->get('a')));

        // } 

        // dump($request->isXmlHttpRequest());

        // $delimiter = '^';
        // $panier = explode($delimiter, $_POST['postArray']);

        // $data = json_decode($request->request->get('data'));
        //    var_dump($data); // Here you can see your vars sent by AJAX
        //    $dataResponse = array("error" => false); //Here data you can send back
        //    return new JsonResponse($dataResponse);

        return $this->render('cart/cartValidator.html.twig');
    }
}
