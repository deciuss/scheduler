<?php


namespace App\Normalisation\Generator;


use App\Entity\Timeslot;
use App\Normalisation\Generator;
use App\Repository\TimeslotRepository;

class TimeslotNeighborNext implements Generator
{

    private TimeslotRepository $timeslotRepository;

    public function getMode() : string
    {
        return 'array';
    }

    public function __construct(
        TimeslotRepository $timeslotRepository
    ){
        $this->timeslotRepository = $timeslotRepository;
    }

    public function generate() : array
    {
        $timeslots = $this->timeslotRepository->findAll();

        $timeslotNeighborNextArray = [];

        foreach ($timeslots as $timeslot) {
            $nextTimeslot = $this->timeslotRepository->findOneBy(["start" => $timeslot->getEnd()]);
            $timeslotNeighborNextArray[$timeslot->getId() - 1] =
                ($nextTimeslot instanceof Timeslot)
                    ? $nextTimeslot->getId() - 1
                    : -1;
        }

        return $timeslotNeighborNextArray;
    }

}