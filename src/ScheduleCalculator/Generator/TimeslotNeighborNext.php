<?php


namespace App\ScheduleCalculator\Generator;


use App\Entity\Plan;
use App\Entity\Timeslot;
use App\ScheduleCalculator\Generator;
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

    public function generate(Plan $plan) : array
    {
        $timeslots = $this->timeslotRepository->findBy(['plan' => $plan], ['id' => 'asc']);

        $timeslotNeighborNextArray = [];

        foreach ($timeslots as $timeslot) {
            $nextTimeslot = $this->timeslotRepository->findOneBy(["start" => $timeslot->getEnd()]);
            $timeslotNeighborNextArray[$timeslot->getMapId()] =
                ($nextTimeslot instanceof Timeslot)
                    ? $nextTimeslot->getMapId()
                    : -1;
        }

        return $timeslotNeighborNextArray;
    }

}