<?php


namespace App\Normalisation\Generator;


use App\Entity\Event;
use App\Normalisation\Condition;
use App\Normalisation\Generator;
use App\Repository\EventRepository;


class EventBlockSize implements Generator
{

    private EventRepository $eventRepository;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function getMode() : string
    {
        return 'string';
    }

    public function __construct(
        EventRepository $eventRepository
    ){
        $this->eventRepository = $eventRepository;
    }

    public function generate() : array
    {
        return array_map(
            fn(Event $event) => $event->getSubject()->getBlockSize(),
            $this->eventRepository->findAll()
        );
    }

}