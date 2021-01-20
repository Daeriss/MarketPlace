<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Entity\User;
use App\Form\ShopType;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/manager/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop_index", methods={"GET"})
     */
    public function index(ShopRepository $shopRepository): Response
    {
        $user = $this->getUser();
        $shopUser = $user->getShop();
        $idShop = $shopUser->getId();
        $shop = $shopRepository->findOneBy(['id' => $idShop]);
        dump($idShop);

        dump($shop);

        return $this->render('shop/index.html.twig', [
            'shop' => $shop,
        ]);
    }

    /**
     * @Route("/new", name="shop_new", methods={"GET","POST"})
     */
    public function new(Request $request,  SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        $shop = new Shop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);
        dump($user->getShop());

        if ($user->getShop() == null) {

            if ($form->isSubmitted() && $form->isValid()) {

                $img = $form->get('img')->getData();


                if ($img) {
                    $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $img->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $img->move(
                            $this->getParameter('photos_directory'),        // NE PAS OUBLIER DE CREER LE DOSSIER
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }

                    $shop->setImg($newFilename);       // ON ENREGISTRE LE NOM DU FICHIER
                } else {
                    $defaultImg = "Asset-1@2x-1.png";
                    $shop->setImg($defaultImg);
                }
                $shop->setUser($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($shop);
                $entityManager->flush();
                dump($shop);

                return $this->redirectToRoute('shop_index');
            }
        } else {
            return $this->redirectToRoute('shop_index');
        }


        return $this->render('shop/new.html.twig', [
            'shop' => $shop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_show", methods={"GET"})
     */
    public function show(Shop $shop): Response
    {
        return $this->render('shop/show.html.twig', [
            'shop' => $shop,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="shop_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Shop $shop): Response
    {
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shop_index');
        }

        return $this->render('shop/edit.html.twig', [
            'shop' => $shop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shop_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Shop $shop): Response
    {
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {

            if ($this->isCsrfTokenValid('delete' . $shop->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($shop);
                $entityManager->flush();
            }
        } else {
            return $this->redirectToRoute('shop_index');
        }

        return $this->redirectToRoute('shop_index');
    }
}
