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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator    ,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $shopkeeper=false;
        $service=false;
        $client=false;

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $shopkeeper = $form->get('shopkeeper')->getData();
            $service = $form->get('service')->getData();
            dump($shopkeeper);
            if ( $form->get('shopkeeper')->getData() == true ) {
                
                $user->setRoles(array('ROLE_SHOPKEEPER'));
                $shopkeeper=true;
               
            } elseif ( $form->get('service')->getData() == true ) {
                
                $user->setRoles(array('ROLE_SERVICE'));
                $service=true;
               
            }
            else {
                $user->setRoles(array('ROLE_MEMBER'));
                $client=true;
                
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



                    
        
             //une fois que le formulaire a été validé, on peut ensuite envoyer les emails
        
         //on envoie le mail si c'est le shopkeeper
         if($shopkeeper)
         {
 
             $message = (new \Swift_Message('Confirmation de commande'))
             ->setFrom('clickncommerce@gmail.com')        
             //->setTo($user->getEmail())
             ->setTo($user->getEmail())
             ->setBody(
                 $this->renderView(
                     // templates/emails/registration.html.twig
                     'email/mailInscriptionShopkeeper.html.twig',
                     ['name' => $user->getUsername()]
                 ),
                 'text/html'
             );
     
             $mailer->send($message);
 
         }  
        
         //on envoie le mail si c'est un producteur de service
         if($service)
         {              
             $message = (new \Swift_Message('Confirmation d\'inscription en tant que fournisseur de service'))
             ->setFrom('clickncommerce@gmail.com')
             //->setTo($user->getEmail())
             ->setTo($user->getEmail())
             ->setBody(
                 $this->renderView(
                     'mailInscriptionServiceProvider.html.twig',
                     ['name' => $user->getUsername(),
                     ]
                 ),
                 'text/html'
             );
 
             $mailer->send($message);
         }
         if($client)
         {
             //on envoie le mail si c'est un cient
              $message = (new \Swift_Message('Confirmation d\'inscription en tant que fournisseur de service'))
             ->setFrom('clickncommerce@gmail.com')
             ->setTo($user->getEmail())
             ->setBody(
                 $this->renderView(
                     'email/mailInscriptionClient.html.twig',
                     ['name' => $user->getUsername(),
                     ]
                 ),
                 'text/html'
             );
 
             $mailer->send($message);
         }
        

         var_dump($client);
         var_dump($service);
         var_dump($shopkeeper);




         //mais dans tous lmes cas one nvoie : 



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
