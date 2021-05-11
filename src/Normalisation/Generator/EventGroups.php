<?php


namespace App\Normalisation\Generator;


use App\Entity\Plan;
use App\Normalisation\Generator;
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

    public function generate() : array
    {
        $events = $this->eventRepository->findBy(['plan' => $plan], ['id' => 'asc']);
        $eventGroups = [];
        foreach ($events as $event) {
            $group = $event->getSubject()->getStudentGroup();
            $eventGroups[$event->getId() - 1][] = $group->getId() - 1;
            foreach($group->getChildren() as $child) {
                $eventGroups[$event->getId() - 1][] = $child->getId() - 1;
            }
        }
        return $eventGroups;
    }

}