<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Timeslot;
use App\Form\TimeslotType;
use App\Repository\TimeslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/timeslot')]
class TimeslotController extends AbstractController
{
    #[Route('/plan/{plan}', name: 'timeslot_index', methods: ['GET'])]
    public function index(TimeslotRepository $timeslotRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('timeslot/index.html.twig', [
            'timeslots' => $timeslotRepository->findBy(['plan' => $plan]),
            'plan' => $plan
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'timeslot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($plan->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $timeslot = new Timeslot();
        $timeslot->setPlan($plan);
        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($timeslot);
            $entityManager->flush();

            return $this->redirectToRoute('timeslot_index', ['plan' => $plan->getId()]);
        }

        return $this->render('timeslot/new.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'timeslot_show', methods: ['GET'])]
    public function show(Timeslot $timeslot): Response
    {
        if ($this->getUser() != $timeslot->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('timeslot/show.html.twig', [
            'timeslot' => $timeslot,
        ]);
    }

    #[Route('/{id}/edit', name: 'timeslot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Timeslot $timeslot): Response
    {
        if ($this->getUser() != $timeslot->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($timeslot->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('timeslot_index', ['plan' => $timeslot->getPlan()->getId()]);
        }

        return $this->render('timeslot/edit.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'timeslot_delete', methods: ['POST'])]
    public function delete(Request $request, Timeslot $timeslot): Response
    {
        if ($this->getUser() != $timeslot->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($timeslot->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $plan = $timeslot->getPlan();

        if ($this->isCsrfTokenValid('delete'.$timeslot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($timeslot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('timeslot_index', ['plan' => $plan->getId()]);
    }
}
