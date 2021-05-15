<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Event;
use App\Entity\Feature;
use App\Entity\Subject;

class SubjectMother
{
    public static function withHoursWithBlockSize(int $hours = 1, int $blockSize = 1) : Subject
    {
        return (new Subject())
            ->setHours($hours)
            ->setBlockSize($blockSize);
    }

    public static function withEvents(Event ...$events) : Subject
    {
        return array_reduce(
            $events,
            fn(Subject $subject, Event $event) => $subject->addEvent($event),
            new Subject()
        );
    }

    public static function withRequiredFeatures(Feature ...$features) : Subject
    {
        return array_reduce(
            $features,
            fn(Subject $subject, Feature $feature) => $subject->addFeature($feature),
            new Subject()
        );
    }

}