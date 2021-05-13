<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Event;
use App\Entity\Feature;
use App\Entity\Room;
use App\Entity\StudentGroup;
use App\Entity\Subject;
use App\Entity\Teacher;

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

    public function givenTeacherHasSubjects(Teacher $teacher, Subject ...$subjects) : void
    {
        foreach ($subjects as $subject) {
            $subject->setTeacher($teacher);
            $teacher->addSubject($subject);
        }
    }

    public function givenSubjectRequiresFeatures(Subject $subject, Feature ...$features)
    {
        foreach ($features as $feature) {
            $subject->addFeature($feature);
        }
    }

    public function givenRoomHasFeatures(Room $room, Feature ...$features)
    {
        foreach ($features as $feature) {
            $room->addFeature($feature);
        }
    }

}