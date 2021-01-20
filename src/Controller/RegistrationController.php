<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $shopkeeper = $form->get('shopkeeper')->getData();
            $service = $form->get('Service')->getData();
            dump($shopkeeper);
            if ( $form->get('shopkeeper')->getData() == true ) {
                
                $user->setRoles(array('ROLE_SHOPKEEPER'));
               
            } elseif ( $form->get('Service')->getData() == true ) {
                
                $user->setRoles(array('ROLE_SERVICE'));
               
            }
            else {
                $user->setRoles(array('ROLE_MEMBER'));
                
            }
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

           $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

             return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                 $authenticator,
                 'main' // firewall name in security.yaml
             );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
