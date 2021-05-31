<?php

namespace App\Controller;

use App\DBAL\PlanStatus;
use App\Entity\Plan;
use App\Form\PlanType;
use App\Repository\PlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plan')]
class PlanController extends AbstractController
{
    #[Route('/', name: 'plan_index', methods: ['GET'])]
    public function index(PlanRepository $planRepository): Response
    {
        return $this->render('plan/index.html.twig', [
            'plans' => $planRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'plan_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $plan = new Plan();
        $plan->setUser($this->getUser());
        $plan->setStatus(PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION);
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('plan_index');
        }

        return $this->render('plan/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'plan_dashboard', methods: ['GET'])]
    public function dashboard(Plan $plan): Response
    {
        return $this->render('plan/dashboard.html.twig', [
            'plan' => $plan,
        ]);
    }

    #[Route('/{id}/edit', name: 'plan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('unauthorized', 401);
        }

        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plan_dashboard', ['id' => $plan->getId()]);
        }

        return $this->render('plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'plan_delete', methods: ['POST'])]
    public function delete(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('unauthorized', 401);
        }

        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('plan_index');
    }
}
