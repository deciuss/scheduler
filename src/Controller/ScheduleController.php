<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Schedule;
use App\Repository\RoomRepository;
use App\Repository\ScheduleEventRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TimeslotRepository;
use App\Scheduler\Scheduler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/schedule')]
class ScheduleController extends AbstractController
{
    public function __construct(
        private Scheduler $facade,
        private TimeslotRepository $timeslotRepository,
        private RoomRepository $roomRepository,
        private ScheduleEventRepository $scheduleEventRepository
    ) {
    }

    #[Route('/plan/{plan}', name: 'schedule_index', methods: ['GET'])]
    public function index(ScheduleRepository $scheduleRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('schedule/index.html.twig', [
            'schedules' => $scheduleRepository->findBy(['plan' => $plan]),
            'plan' => $plan,
        ]);
    }

    #[Route('/generate/plan/{plan}', name: 'schedule_generate', methods: ['POST'])]
    public function generate(Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        $this->facade->generate($plan->getId());

        return new Response(status: 200);
    }

    #[Route('/info/plan/{plan}', name: 'schedule_generator_info', methods: ['GET'])]
    public function generatorInfo(Plan $plan): JsonResponse
    {
        if ($this->getUser() != $plan->getUser()) {
            return new JsonResponse(['error' => 'Unauthorized to access this resource'], 401);
        }

        return new JsonResponse($this->facade->getReportForPlan($plan->getId()));
    }

    #[Route('/new/plan/{plan}', name: 'schedule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('schedule/new.html.twig', [
            'plan' => $plan,
        ]);
    }

    #[Route('/{id}/group/{groupId}/teacher/{teacherId}', name: 'schedule_show', methods: ['GET'])]
    public function show(Schedule $schedule, $groupId, $teacherId): Response
    {
        if ($this->getUser() != $schedule->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        $scheduleEvents = $this->scheduleEventRepository->findBy(['schedule' => $schedule]);
        $rooms = $this->roomRepository->findBy(['plan' => $schedule->getPlan()], ['map_id' => 'ASC']);
        $timeslots = $this->timeslotRepository->findBy(['plan' => $schedule->getPlan()], ['map_id' => 'ASC']);

        $events = [];

        for ($rowIndex = 0; $rowIndex < count($timeslots); ++$rowIndex) {
            $events[$rowIndex] = [];
            for ($colIndex = 0; $colIndex < count($rooms); ++$colIndex) {
                $events[$rowIndex][$colIndex] = null;
            }
        }

        foreach ($scheduleEvents as $scheduleEvent) {
            if (
                'all' != $groupId
                && $groupId != $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getId()
                && (null == $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getParent()
                    || $groupId != $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getParent()->getId())
            ) {
                continue;
            }

            if (
                'all' != $teacherId
                && $teacherId != $scheduleEvent->getEvent()->getSubject()->getTeacher()->getId()
            ) {
                continue;
            }

            $events[$scheduleEvent->getTimeslot()->getMapId()][$scheduleEvent->getRoom()->getMapId()]
                = $scheduleEvent;
        }

        return $this->render('schedule/show.html.twig', [
            'schedule' => $schedule,
            'rooms' => $rooms,
            'timeslots' => $timeslots,
            'events' => $events,
        ]);
    }

    #[Route('/{id}', name: 'schedule_delete', methods: ['POST'])]
    public function delete(Request $request, Schedule $schedule): Response
    {
        if ($this->getUser() != $schedule->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        $plan = $schedule->getPlan();

        if ($this->isCsrfTokenValid('delete'.$schedule->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($schedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('schedule_index', $plan->getId());
    }
}
