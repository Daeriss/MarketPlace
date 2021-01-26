<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShopRepository;
use DateInterval;
use DateTime;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{


    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     */
    public function index(CalendarRepository $calendarRepository): Response
    {

        $user = $this->getUser();
        $shop = $user->getShop();
        $idShop = $shop->getId();

        $listerdv = $calendarRepository->findBy(
            ['shop' => $shop],
            []
        );

        return $this->render('product/index.html.twig', [
            'calendars' => $listerdv
        ]);
    }

    /**
     * @Route("/nouveau/appointment", name="new_appointment", methods={"GET","PUT"})
     */
    public function new_appointment(?Calendar $calendar, Request $request): Response
    {
        //decode les données recu en json
        $donnees = json_decode($request->getContent());
        dump($donnees);

        if (isset($donnees)) {
            //si il y a des données
            $start = new DateTime($donnees->start);
            dump($start);
            //on stock la date de début et le shop
            $this->session->set('appointmentStart', $start);
            if (isset($donnees->shopId)) {
                $shopid = $donnees->shopId;
                $this->session->set('shopId', $shopid);
            }
        }

        return $this->redirectToRoute('calendar_new');
    }

    /**
     * @Route("/new", name="calendar_new", methods={"GET","POST", "PUT"})
     */
    public function new(Request $request, ShopRepository $shopRepository, ServicesRepository $servicesRepository): Response
    {
        // on récpère l'heure sélectionnée et le shop 
        $start = $this->session->get('appointmentStart');
        $shopid = $this->session->get('shopId');
        dump($start);

        $user = $this->getUser();
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if (isset($start)){
            if ($user != null) {
                if (in_array('ROLE_SERVICE', $user->getRoles())) {
                    //si lapersonne qui prend un rdv est le prestataire on recupère sa boutique
                    $shop = $user->getShop();
                } else {
                    // si c'est un client on récupère la boutique provenant des données envoyées en json
                    $shop = $shopRepository->findOneBy(['id' => $shopid]);
                }
                if ($form->isSubmitted() && $form->isValid()) {

                    //on récupère la prestation souhaité
                    $serviceObject = $form->get('Prestation')->getData('choice_label');

                    // on trouve la ligne de la prestation souhaité
                    $service = $servicesRepository->findOneBy([
                        'name' => $serviceObject->getName(),
                    ]);

                    dump($service->getDuration());
                    // on récupère la durée de la prestation
                    $dureeDateTime = $service->getDuration();
                    // on récupère l'heure
                    $dureeHours = $dureeDateTime->format("h");
                    //et laminute de la prestation
                    $dureeMinutes = $dureeDateTime->format("i");

                    dump($dureeHours);
                    dump($dureeMinutes);
                    dump($start);

                    // on doit créer un autre objet date time pour l'heure de fin sinon il modifie l'heure de début parce qu'il les considère comme un seul objet meme si les variable ont des nom différents
                    $startforendstring = $start->format("Y-m-d H:i:s");
                    $startforend = new \DateTime($startforendstring);

                    // on ajoute a notre nouvel objet date time la durée
                    $endhours = $startforend->modify("+{$dureeHours} hours");
                    $endtime = $endhours->modify("+{$dureeMinutes} minutes");

                    //et on a deux date time le début de la prestation et la fin
                    dump($start);
                    dump($endtime);
                    $calendar->setBackgroundColor("#884A65");
                    $calendar->setStart($start);
                    $calendar->setEnd($endtime);
                    $calendar->setTitle($serviceObject->getName());
                    $calendar->setShop($shop);
                    
                    if (in_array('ROLE_MEMBER', $user->getRoles())) {
                        $calendar->setUser($user);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($calendar);
                    $entityManager->flush();

                    if (in_array('ROLE_SERVICE', $user->getRoles())) {
                        //si c'est le prestataire on le redirige vers ses rdv
                        return $this->redirectToRoute('appointment');
                    } else {
                        //si c'est un client on le redirige vers ses rdv
                        return $this->redirectToRoute('app_account_rdv');
                    }
                    $this->session->clear();
                }

                return $this->render('calendar/new.html.twig', [
                    'calendar' => $calendar,
                    'form' => $form->createView(),
                ]);
            } else {
                //si la personne n'est pas connecté elle se connecte
                return $this->redirectToRoute('app_login');
            }
        }else {
            return $this->redirectToRoute('appointment', [
                'error' => 'Veuillez sélectionner une date',
            ]);
        }
    }

    /**
     * @Route("/{id}", name="calendar_show", methods={"GET"})
     */
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendar $calendar, ServicesRepository $servicesRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on récupère la prestation souhaité
            $serviceObject = $form->get('Prestation')->getData('choice_label');

            // on trouve la ligne de la prestation souhaité
            $service = $servicesRepository->findOneBy([
                'name' => $serviceObject->getName(),
            ]);

            dump($service->getDuration());
            // on récupère la durée de la prestation
            $dureeDateTime = $service->getDuration();
            // on récupère l'heure
            $dureeHours = $dureeDateTime->format("h");
            //et laminute de la prestation
            $dureeMinutes = $dureeDateTime->format("i");

            dump($dureeHours);
            dump($dureeMinutes);
           

            // on doit créer un autre objet date time pour l'heure de fin sinon il modifie l'heure de début parce qu'il les considère comme un seul objet meme si les variable ont des nom différents
            $start = $form->get('start')->getData();
            $startforendstring = $start->format("Y-m-d H:i:s");
            $startforend = new \DateTime($startforendstring);

            // on ajoute a notre nouvel objet date time la durée
            $endhours = $startforend->modify("+{$dureeHours} hours");
            $endtime = $endhours->modify("+{$dureeMinutes} minutes");

            //et on a deux date time le début de la prestation et la fin
            dump($start);
            dump($endtime);

            $calendar->setStart($start);
            $calendar->setEnd($endtime);
            $this->getDoctrine()->getManager()->flush();


            if (in_array('ROLE_SERVICE', $user->getRoles())) {
                return $this->redirectToRoute('appointment');

            }else {
                return $this->redirectToRoute('app_account_rdv');

            }
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        if (in_array('ROLE_SERVICE', $user->getRoles())) {
            return $this->redirectToRoute('appointment');

        }else {
            return $this->redirectToRoute('app_account_rdv');

        }
    }
}
