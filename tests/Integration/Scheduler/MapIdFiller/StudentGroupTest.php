<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\MapIdFiller;

use App\Scheduler\MapIdFiller\StudentGroupFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\MapIdFiller\StudentGroupFiller
 */
class StudentGroupTest extends IntegrationTestCase
{
    public function test_if_fills_room_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");

        $this->schedulerContext->givenStudentGroupExists("studentGroup1", $plan);
        $this->schedulerContext->givenStudentGroupExists("studentGroup2", $plan);
        $this->schedulerContext->givenStudentGroupExists("studentGroup3", $plan);

        (new StudentGroupFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getStudentGroupRepository()
        ))($plan);

        $this->assertEquals(0, ($this->schedulerContext->getStudentGroupRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getStudentGroupRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getStudentGroupRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}