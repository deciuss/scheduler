<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/plan/{plan}', name: 'teacher_index', methods: ['GET'])]
    public function index(TeacherRepository $teacherRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('teacher/index.html.twig', [
            'teachers' => $teacherRepository->findBy(['plan' => $plan]),
            'plan' => $plan,
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'teacher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($plan->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $teacher = new Teacher();
        $teacher->setPlan($plan);
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_index', ['plan' => $plan->getId()]);
        }

        return $this->render('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'teacher_show', methods: ['GET'])]
    public function show(Teacher $teacher): Response
    {
        if ($this->getUser() != $teacher->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    #[Route('/{id}/edit', name: 'teacher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teacher $teacher): Response
    {
        if ($this->getUser() != $teacher->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($teacher->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teacher_index', ['plan' => $teacher->getPlan()->getId()]);
        }

        return $this->render('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'teacher_delete', methods: ['POST'])]
    public function delete(Request $request, Teacher $teacher): Response
    {
        if ($this->getUser() != $teacher->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        if ($teacher->getPlan()->isLocked()) {
            return new Response('Plan cannot be altered at this point', 409);
        }

        $plan = $teacher->getPlan();

        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($teacher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_index', ['plan' => $plan->getId()]);
    }
}
