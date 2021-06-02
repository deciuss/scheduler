<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Schedule;
use App\Entity\ScheduleEvent;
use App\Form\ScheduleType;
use App\Repository\RoomRepository;
use App\Repository\ScheduleEventRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TimeslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/schedule')]
class ScheduleController extends AbstractController
{

    public function __construct(
        TimeslotRepository $timeslotRepository,
        RoomRepository $roomRepository,
        ScheduleEventRepository $scheduleEventRepository
    ){
        $this->timeslotRepository = $timeslotRepository;
        $this->roomRepository = $roomRepository;
        $this->scheduleEventRepository = $scheduleEventRepository;
    }


    #[Route('/plan/{plan}', name: 'schedule_index', methods: ['GET'])]
    public function index(ScheduleRepository $scheduleRepository, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        return $this->render('schedule/index.html.twig', [
            'schedules' => $scheduleRepository->findAll(),
            'plan' => $plan
        ]);
    }

    #[Route('/new/plan/{plan}', name: 'schedule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plan $plan): Response
    {
        if ($this->getUser() != $plan->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

//        $schedule = new Schedule();
//        $form = $this->createForm(ScheduleType::class, $schedule);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($schedule);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('schedule_index');
//        }
//
//        return $this->render('schedule/new.html.twig', [
//            'schedule' => $schedule,
//            'form' => $form->createView(),
//        ]);
    }

    #[Route('/{id}/group/{groupId}/teacher/{teacherId}', name: 'schedule_show', methods: ['GET'])]
    public function show(Schedule $schedule, $groupId, $teacherId): Response
    {
        if ($this->getUser() != $schedule->getPlan()->getUser()) {
            return new Response('Unauthorized to access this resource', 401);
        }

        $criteria = ["schedule" => $schedule];

        $scheduleEvents = $this->scheduleEventRepository->findBy($criteria);

        $rooms = $this->roomRepository->findAll();
        $timeslots = $this->timeslotRepository->findAll();

        $table = [];

        for ($i = 0; $i < count($timeslots) + 1; $i++){
            for ($j = 0; $j < count($rooms) + 1; $j++) {
                $table[$i][$j] = "";
            }
        }

        for ($i = 0; $i < count($timeslots); $i++) {
            $table[$i+1][0] = $timeslots[$i]->getStart()->format("D H:i");
        }

        for ($i = 0; $i < count($rooms); $i++) {
            $table[0][$i+1] = $rooms[$i]->getName();
        }

        /** @var $scheduleEvent ScheduleEvent */
        foreach ($scheduleEvents as $scheduleEvent) {

            if (
                $groupId != "all"
                && $groupId != $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getId()
                && (null == $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getParent()
                    || $groupId != $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getParent()->getId())
            ) {
                continue;
            }

            if ($teacherId != "all" && $teacherId != $scheduleEvent->getEvent()->getSubject()->getTeacher()->getId()) {
                continue;
            }

            $table[$scheduleEvent->getTimeslot()->getId()][$scheduleEvent->getRoom()->getId()] .=
                $scheduleEvent->getEvent()->getSubject()->getName() . "<br/>"
                . $scheduleEvent->getEvent()->getSubject()->getTeacher()->getName() . "<br/>"
                . $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getName() . "<br/>"
            ;
        }

        return $this->render('schedule/show.html.twig', [
            'controller_name' => 'ScheduleShowController',
            'schedule_events' => $scheduleEvents,
            'table' => $table,
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
