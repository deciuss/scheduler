<?php


namespace App\Normalisation\Generator;


use App\Entity\Plan;
use App\Normalisation\Generator;
use App\Repository\EventRepository;

class EventTeacher implements Generator
{

    private EventRepository $eventRepository;

    public function getMode() : string
    {
        return 'array';
    }

    public function __construct(
        EventRepository $eventRepository
    ){
        $this->eventRepository = $eventRepository;
    }

    public function generate(Plan $plan) : array
    {
        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        $eventTeacher = [];
        foreach ($events as $event) {
            $eventTeacher[$event->getMapId()] = $event->getSubject()->getTeacher()->getMapId();
        }
        return $eventTeacher;
    }

}