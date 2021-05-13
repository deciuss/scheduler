<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Event;
use App\Entity\Subject;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function givenSubjectHasEvents(Subject $subject, Event ...$events) : void
    {
        foreach ($events as $event) {
            $subject->addEvent($event);
        }
    }
}