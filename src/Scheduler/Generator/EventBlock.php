<?php

declare(strict_types=1);

namespace App\Scheduler\Generator;

use App\Entity\Subject;
use App\Scheduler\Condition\EventBlock\IsOfTheSameSubject;
use App\Scheduler\Generator;

class EventBlock implements Generator
{

    public function getMode() : string
    {
        return 'intOneToMany';
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