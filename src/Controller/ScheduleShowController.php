<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ScheduleEvent;
use App\Repository\RoomRepository;
use App\Repository\ScheduleEventRepository;
use App\Repository\TimeslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ScheduleShowController extends AbstractController
{
    private TimeslotRepository $timeslotRepository;
    private RoomRepository $roomRepository;

    private ScheduleEventRepository $scheduleEventRepository;

    public function __construct(
        TimeslotRepository $timeslotRepository,
        RoomRepository $roomRepository,
        ScheduleEventRepository $scheduleEventRepository
    ){
        $this->timeslotRepository = $timeslotRepository;
        $this->roomRepository = $roomRepository;
        $this->scheduleEventRepository = $scheduleEventRepository;
    }

    #[Route('/schedule/show/{scheduleId}/group/{groupId}/teacher/{teacherId}', name: 'schedule_show')]
    public function index(int $scheduleId, $groupId, $teacherId): Response
    {
        $criteria = ["schedule" => $scheduleId];

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

        return $this->render('schedule_show/index.html.twig', [
            'controller_name' => 'ScheduleShowController',
            'schedule_events' => $scheduleEvents,
            'table' => $table,
        ]);
    }

}
