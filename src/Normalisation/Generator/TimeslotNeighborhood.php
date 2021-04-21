<?php


namespace App\Normalisation\Generator;


use App\Normalisation\Condition;
use App\Normalisation\Condition\TimeslotNeighborhood\TimeslotIsNeighbor;
use App\Normalisation\Generator;
use App\Normalisation\TruthMatrixGenerator;
use App\Repository\TimeslotRepository;

class TimeslotNeighborhood implements Generator
{

    private TruthMatrixGenerator $truthMatrixGenerator;
    private TimeslotRepository $timeslotRepository;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        TimeslotRepository $timeslotRepository,
        TimeslotIsNeighbor $timeslotIsNeighbor
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->timeslotRepository = $timeslotRepository;
        $this->conditions[] = $timeslotIsNeighbor;
    }

    public function generate() : array
    {
        $timeslots = $this->timeslotRepository->findAll();
        return $this->truthMatrixGenerator->generate($timeslots, $timeslots, ...$this->conditions);
    }

}