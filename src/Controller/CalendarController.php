<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShopRepository;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
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
     * @Route("/new", name="calendar_new", methods={"GET","POST"})
     */
    public function new(Request $request, ShopRepository $shopRepository): Response
    {
        $user = $this->getUser();
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($user != null) {

            if (in_array('ROLE_SERVICE', $user->getRoles())) {

                $shop = $user->getShop();
            } else {
                $requestid = Request::createFromGlobals();
                $shopid = $requestid->query->get('id');

                $shop = $shopRepository->findOneBy(['id' => $shopid]);
            }
            
            if ($form->isSubmitted() && $form->isValid()) {

                $calendar->setShop($shop);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($calendar);
                $entityManager->flush();

                if (in_array('ROLE_SERVICE', $user->getRoles())) {

                    return $this->redirectToRoute('appointment');
                }else {
               
                    return $this->redirectToRoute('shop');
                }
            }

            return $this->render('calendar/new.html.twig', [
                'calendar' => $calendar,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
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
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
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
        if ($this->isCsrfTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index');
    }
}
