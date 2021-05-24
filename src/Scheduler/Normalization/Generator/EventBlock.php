<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Subject;
use App\Scheduler\Condition\EventBlock\IsOfTheSameSubject;
use App\Scheduler\Normalization\Generator;

class EventBlock implements Generator
{
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