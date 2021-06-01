<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\StudentGroup;
use App\Form\StudentGroupType;
use App\Repository\StudentGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student_group')]
class StudentGroupController extends AbstractController
{
    #[Route('/plan/{plan}', name: 'student_group_index', methods: ['GET'])]
    public function index(StudentGroupRepository $studentGroupRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('student_group/index.html.twig', [
            'student_groups' => $studentGroupRepository->findBy(['plan' => $plan]),
            'plan' => $plan
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'student_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($plan->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $studentGroup = new StudentGroup();
        $studentGroup->setPlan($plan);
        $form = $this->createForm(StudentGroupType::class, $studentGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studentGroup);
            $entityManager->flush();

            return $this->redirectToRoute('student_group_index', ['plan' => $plan->getId()]);
        }

        return $this->render('student_group/new.html.twig', [
            'student_group' => $studentGroup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'student_group_show', methods: ['GET'])]
    public function show(StudentGroup $studentGroup): Response
    {
        if ($this->getUser() != $studentGroup->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('student_group/show.html.twig', [
            'student_group' => $studentGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'student_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StudentGroup $studentGroup): Response
    {
        if ($this->getUser() != $studentGroup->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($studentGroup->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $form = $this->createForm(StudentGroupType::class, $studentGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_group_index', ['plan' => $studentGroup->getPlan()->getId()]);
        }

        return $this->render('student_group/edit.html.twig', [
            'student_group' => $studentGroup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'student_group_delete', methods: ['POST'])]
    public function delete(Request $request, StudentGroup $studentGroup): Response
    {
        if ($this->getUser() != $studentGroup->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($studentGroup->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $plan = $studentGroup->getPlan();

        if ($this->getUser() != $studentGroup->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($this->isCsrfTokenValid('delete'.$studentGroup->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($studentGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_group_index', ['plan' => $plan->getId()]);
    }
}
