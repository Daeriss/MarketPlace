<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

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
    public function commandes(): Response
    {
        return $this->render('member/orders.html.twig');
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
}
