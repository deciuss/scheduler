<?php


namespace App\ScheduleCalculator\Generator;


use App\Entity\Plan;
use App\ScheduleCalculator\Generator;
use App\Repository\EventRepository;

class EventGroups implements Generator
{

    private EventRepository $eventRepository;

    public function getMode() : string
    {
        return 'oneToMany';
    }

    public function __construct(
        EventRepository $eventRepository
    ){
        $this->eventRepository = $eventRepository;
    }

    public function generate(Plan $plan) : array
    {
        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        $eventGroups = [];
        foreach ($events as $event) {
            $group = $event->getSubject()->getStudentGroup();
            $eventGroups[$event->getMapId()][] = $group->getMapId();
            foreach($group->getChildren() as $child) {
                $eventGroups[$event->getMapId()][] = $child->getMapId();
            }
        }
        return $eventGroups;
    }

}