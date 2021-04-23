<?php


namespace App\Normalisation\Generator;


use App\Normalisation\Condition;
use App\Normalisation\Condition\EventSameSubject\IsOfTheSameSubject;
use App\Normalisation\Generator;
use App\Normalisation\TruthMatrixGenerator;
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
        return 'blocks';
    }

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        SubjectRepository $subjectRepository,
        EventRepository $eventRepository,
        IsOfTheSameSubject $isOfTheSameSubject
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->subjectRepository = $subjectRepository;
        $this->eventRepository = $eventRepository;
        $this->conditions[] = $isOfTheSameSubject;
    }

    public function generate() : array
    {
        $subjects = $this->subjectRepository->findAll();

        $blocks = [];
        $blockIndex = -1;

        foreach ($subjects as $subject) {
            $remainingBlockSize = 0;
            foreach ($this->eventRepository->findBy(["subject" => $subject]) as $event) {
                if ($remainingBlockSize <= 0) {
                    $blockIndex++;
                    $remainingBlockSize = $subject->getBlockSize();
                }
                $blocks[$blockIndex][] = $event->getId() - 1;
                $remainingBlockSize--;
            }
        }

        return $blocks;
    }

}