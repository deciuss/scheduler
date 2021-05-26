<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\Normalization\MapIdFiller;

use App\Scheduler\Normalization\MapIdFiller\TeacherFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Normalization\MapIdFiller\TeacherFiller
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
        ))($plan->getId());

        $this->schedulerContext->getEntityManager()->clear();

        $this->assertEquals(0, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getTeacherRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}