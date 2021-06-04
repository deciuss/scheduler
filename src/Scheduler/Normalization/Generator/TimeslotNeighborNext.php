<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Timeslot;
use App\Scheduler\Normalization\Generator;

class TimeslotNeighborNext implements Generator
{
    public function generate(Timeslot ...$timeslots): array
    {
        $timeslotNeighborNextArray = [];
        for ($i = 0; $i < count($timeslots); ++$i) {
            $timeslotNeighborNextArray[$timeslots[$i]->getMapId()] = array_reduce(
                $timeslots,
                function (int $carry, Timeslot $nextTimeslotCandidate) use ($timeslots, $i) {
                    if ($carry < 0 && $timeslots[$i]->getEnd() == $nextTimeslotCandidate->getStart()) {
                        $carry = $nextTimeslotCandidate->getMapId();
                    }

                    return $carry;
                },
                -1
            );
        }

        return $timeslotNeighborNextArray;
    }
}
