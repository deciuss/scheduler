<?php


namespace App\Normalisation\Generator;


use App\Entity\Timeslot;
use App\Normalisation\Generator;
use App\Repository\EventRepository;
use App\Repository\TimeslotRepository;

class EventGroup implements Generator
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
        $eventGroupArray = [];
        foreach ($events as $event) {
            $eventGroupArray[$event->getId() - 1] = $event->getSubject()->getStudentGroup()->getId() - 1;
        }

        return $eventGroupArray;
    }

}