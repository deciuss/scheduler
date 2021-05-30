<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    #[Route('/plan/{plan}', name: 'subject_index', methods: ['GET'])]
    public function index(SubjectRepository $subjectRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('unauthorized', 401);
        }

        return $this->render('subject/index.html.twig', [
            'subjects' => $subjectRepository->findBy(['plan' =>  $plan]),
            'plan' => $plan
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('unauthorized', 401);
        }

        $subject = new Subject();
        $subject->setPlan($plan);
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('subject_index', ['plan' => $plan->getId()]);
        }

        return $this->render('subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $subject): Response
    {
        if ($this->getUser() != $subject->getPlan()->getUser()) {
            return new Response('unauthorized', 401);
        }

        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subject_index', ['plan' => $subject->getPlan()->getId()]);
        }

        return $this->render('subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->getUser() != $subject->getPlan()->getUser()) {
            return new Response('unauthorized', 401);
        }

        $plan = $subject->getPlan();

        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subject_index', ['plan' => $plan->getId()]);
    }
}
