<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Plan;
use App\Form\FeatureType;
use App\Repository\FeatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/feature')]
class FeatureController extends AbstractController
{
    #[Route('/plan/{plan}', name: 'feature_index', methods: ['GET'])]
    public function index(FeatureRepository $featureRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('feature/index.html.twig', [
            'features' => $featureRepository->findBy(['plan' => $plan]),
            'plan' => $plan,
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'feature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($plan->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $feature = new Feature();
        $feature->setPlan($plan);
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feature);
            $entityManager->flush();

            return $this->redirectToRoute('feature_index', ['plan' => $plan->getId()]);
        }

        return $this->render('feature/new.html.twig', [
            'feature' => $feature,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'feature_show', methods: ['GET'])]
    public function show(Feature $feature): Response
    {
        if ($this->getUser() != $feature->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('feature/show.html.twig', [
            'feature' => $feature,
        ]);
    }

    #[Route('/{id}/edit', name: 'feature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Feature $feature): Response
    {
        if ($this->getUser() != $feature->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($feature->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('feature_index');
        }

        return $this->render('feature/edit.html.twig', [
            'feature' => $feature,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'feature_delete', methods: ['POST'])]
    public function delete(Request $request, Feature $feature): Response
    {
        if ($this->getUser() != $feature->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($feature->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $plan = $feature->getPlan();

        if ($this->getUser() != $feature->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($this->isCsrfTokenValid('delete'.$feature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($feature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('feature_index', ['plan' => $plan->getId()]);
    }
}
