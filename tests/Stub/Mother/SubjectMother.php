<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Event;
use App\Entity\Feature;
use App\Entity\StudentGroup;
use App\Entity\Subject;
use App\Entity\Teacher;

class SubjectMother
{
    public static function withHoursWithBlockSize(int $hours, int $blockSize) : Subject
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

    public static function withTeacher(Teacher $teacher)
    {
        return (new Subject())
            ->setTeacher($teacher);
    }

    public static function withStudentGroup(StudentGroup $studentGroup)
    {
        return (new Subject())
            ->setStudentGroup($studentGroup);
    }

}