<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\MapIdFiller;

use App\Scheduler\MapIdFiller\TeacherFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\MapIdFiller\TeacherFiller
 */
class TeacherFillerTest extends IntegrationTestCase
{
    public function test_if_fills_teacher_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");

        $this->schedulerContext->givenTeacherExists("teacher1", $plan);
        $this->schedulerContext->givenTeacherExists("teacher2", $plan);
        $this->schedulerContext->givenTeacherExists("teacher3", $plan);

        (new TeacherFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getTeacherRepository()
        ))($plan);

        $this->assertEquals(0, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}