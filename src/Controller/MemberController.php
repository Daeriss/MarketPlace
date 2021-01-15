<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }


    /**
     * @Route("/mon-compte/modifier", name="app_account_edit")
     */
    public function edit(): Response
    {
        return $this->render('member/edit.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }
}
