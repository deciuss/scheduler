<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\MapIdFiller;

use App\Scheduler\MapIdFiller\EventFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\MapIdFiller\EventFiller
 */
class EventFillerTest extends IntegrationTestCase
{
    public function test_if_fills_event_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");
        $teacher = $this->schedulerContext->givenTeacherExists("teacher", $plan);
        $studentGroup = $this->schedulerContext->givenStudentGroupExists('group', $plan);
        $subject = $this->schedulerContext->givenSubjectExists('subject', $plan, $teacher, $studentGroup);

        $this->schedulerContext->givenEventExists('event1', $subject);
        $this->schedulerContext->givenEventExists('event2', $subject);
        $this->schedulerContext->givenEventExists('event3', $subject);

        (new EventFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getEventRepository()
        ))($plan);

        $this->assertEquals(0, ($this->schedulerContext->getEventRepository()->findBy(['subject' => $subject], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getEventRepository()->findBy(['subject' => $subject], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getEventRepository()->findBy(['subject' => $subject], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}