<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Event;
use App\Entity\StudentGroup;
use App\Entity\Subject;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function givenSubjectHasEvents(Subject $subject, Event ...$events) : void
    {
        foreach ($events as $event) {
            $event->setSubject($subject);
            $subject->addEvent($event);
        }
    }

    public function givenStudentGroupHasSubjects(StudentGroup $studentGroup, Subject ...$subjects) : void
    {
        foreach ($subjects as $subject) {
            $subject->setStudentGroup($studentGroup);
            $studentGroup->addSubject($subject);
        }
    }

}