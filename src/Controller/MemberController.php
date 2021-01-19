<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Repository\OrderRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('member/index.html.twig');
    }

    /**
     * @Route("/mon-compte/mes-commandes", name="app_account_orders")
     */
    public function commandes(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $listeCommandes = $orderRepository->findBy(
            ['user' => $user],
            []
        );
        return $this->render('member/orders.html.twig', [
            'orders' => $listeCommandes
        ]);
    }

    /**
     * @Route("/mon-compte/modifier", name="app_account_edit" , methods={"GET","POST"})
     */
    public function edit(request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        dump($user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('message', 'profil mis à jour');
            // return $this->redirectToRoute('modifier');
        }

        return $this->render('member/edit.html.twig', [
            'form' => $form->createView()
        ]);
    
    }


/**
     * @Route("/mon-compte/modifier/motdepasse", name="app_account_edit_mdp" , methods={"GET","POST"})
     */
    public function editPass(request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            // Verification si les deux MDP sont identique -- SECURITE
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'mot de passe a été mis à jour');
                return $this->redirectToRoute('app_account_edit');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }



        return $this->render('member/editpass.html.twig');
    
    }
}
