<?php


namespace App\Normalisation\Condition\EventSameSubject;


use App\Entity\Event;
use App\Normalisation\Condition;

class IsOfTheSameSubject implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Event, 'Invalid type');
        return $item1->getSubject()->getId() !== $item2->getSubject()->getId();
    }
}