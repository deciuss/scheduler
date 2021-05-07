<?php


namespace App\Normalisation\Generator;


use App\Entity\Timeslot;
use App\Normalisation\Generator;
use App\Repository\EventRepository;
use App\Repository\TimeslotRepository;

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

    public function generate() : array
    {
        $events = $this->eventRepository->findAll();
        $eventTeacher = [];
        foreach ($events as $event) {
            $eventTeacher[$event->getId() - 1] = $event->getSubject()->getTeacher()->getId() - 1;
        }
        return $eventTeacher;
    }

}