<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\Normalization;

use App\Scheduler\Normalization\EventFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Normalization\EventFiller
 */
class EventFillerTest extends IntegrationTestCase
{
    public function test_if_fills_events() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");
        $teacher = $this->schedulerContext->givenTeacherExists("teacher", $plan);
        $studentGroup = $this->schedulerContext->givenStudentGroupExists('group', $plan);
        $subject1 = $this->schedulerContext->givenSubjectExists('subject1', $plan, $teacher, $studentGroup, 4);
        $subject2 = $this->schedulerContext->givenSubjectExists('subject2', $plan, $teacher, $studentGroup, 7);

        (new EventFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getSubjectRepository()
        ))($plan);

        $this->schedulerContext->getEntityManager()->clear();

        $this->assertEquals(4, ($this->schedulerContext->getEventRepository()->count(['subject' => $subject1])));
        $this->assertEquals(7, ($this->schedulerContext->getEventRepository()->count(['subject' => $subject2])));
    }
}