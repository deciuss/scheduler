<?php


namespace App\ScheduleCalculator\Generator;


use App\Entity\Subject;
use App\ScheduleCalculator\Condition\EventBlock\IsOfTheSameSubject;
use App\ScheduleCalculator\Generator;

class EventBlock implements Generator
{

    public function getMode() : string
    {
        return 'oneToMany';
    }

    public function generate(Subject ...$subjects) : array
    {
        $blocks = [];
        $blockIndex = -1;

        foreach ($subjects as $subject) {
            $remainingBlockSize = 0;
            foreach ($subject->getEvents() as $event) {
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