<?php

namespace App\Controller;

use App\Entity\ScheduleEvent;
use App\Repository\RoomRepository;
use App\Repository\ScheduleEventRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TimeslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/schedule/show/{id}', name: 'schedule_show')]
    public function index(int $id): Response
    {
//        $this->scheduleEventRepository->findByScheduleId(['schedule' => $id]);
        $scheduleEvents = $this->scheduleEventRepository->findBySchedule($id);

//        var_dump(count($scheduleEvents));




//        $table = [$this->roomRepository->count([])][$this->timeslotRepository->count([])];


//        $numberOfRooms = $this->roomRepository->count([]);
//        $numberOfTimeslots = $this->timeslotRepository->count([]);

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
//            $color = $this->color($scheduleEvent->getEvent()->getSubject()->getId());
            $table[$scheduleEvent->getTimeslot()->getId()][$scheduleEvent->getRoom()->getId()] .=
//                "<div style='background-color: $color'>"
                $scheduleEvent->getEvent()->getSubject()->getName() . "<br/>"
                . $scheduleEvent->getEvent()->getSubject()->getTeacher()->getName() . "<br/>"
                . $scheduleEvent->getEvent()->getSubject()->getStudentGroup()->getName() . "<br/>"
//                . "</div>"

                ;


        }

        return $this->render('schedule_show/index.html.twig', [
            'controller_name' => 'ScheduleShowController',
            'schedule_events' => $scheduleEvents,
            'table' => $table,
        ]);
    }

}
