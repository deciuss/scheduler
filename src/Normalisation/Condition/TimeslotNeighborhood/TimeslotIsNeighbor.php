<?php


namespace App\Normalisation\Condition\TimeslotNeighborhood;


use App\Entity\Timeslot;
use App\Normalisation\Condition;

class TimeslotIsNeighbor implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Timeslot, 'Invalid type');
        assert($item2 instanceof Timeslot, 'Invalid type');
        return $item1->getStart() == $item2->getEnd() || $item1->getEnd() == $item2->getStart();
    }
}