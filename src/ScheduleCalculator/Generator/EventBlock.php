<?php


namespace App\ScheduleCalculator\Generator;


use App\Entity\Plan;
use App\ScheduleCalculator\Condition;
use App\ScheduleCalculator\Condition\EventBlock\IsOfTheSameSubject;
use App\ScheduleCalculator\Generator;
use App\ScheduleCalculator\TruthMatrixGenerator;
use App\Repository\EventRepository;
use App\Repository\SubjectRepository;

class EventBlock implements Generator
{

    private TruthMatrixGenerator $truthMatrixGenerator;
    private SubjectRepository $subjectRepository;
    private EventRepository $eventRepository;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function getMode() : string
    {
        return 'oneToMany';
    }

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        SubjectRepository $subjectRepository,
        EventRepository $eventRepository
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->subjectRepository = $subjectRepository;
        $this->eventRepository = $eventRepository;
    }

    public function generate(Plan $plan) : array
    {
        $subjects = $this->subjectRepository->findBy(['plan' => $plan], ['id' => 'asc']);

        $blocks = [];
        $blockIndex = -1;

        foreach ($subjects as $subject) {
            $remainingBlockSize = 0;
            foreach ($this->eventRepository->findBy(["subject" => $subject]) as $event) {
                if ($remainingBlockSize <= 0) {
                    $blockIndex++;
                    $remainingBlockSize = $subject->getBlockSize();
                }
                $blocks[$blockIndex][] = $event->getMapId();
                $remainingBlockSize--;
            }
        }

        return $blocks;
    }

}