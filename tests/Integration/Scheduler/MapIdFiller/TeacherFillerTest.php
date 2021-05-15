<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\MapIdFiller;

use App\Scheduler\MapIdFiller\TeacherFiller;
use App\Tests\Integration\IntegrationTestCase;

class TeacherFillerTest extends IntegrationTestCase
{
    public function test_if_fills_teacher_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");

        $this->schedulerContext->givenTeacherForPlanExists("teacher1", $plan);
        $this->schedulerContext->givenTeacherForPlanExists("teacher2", $plan);
        $this->schedulerContext->givenTeacherForPlanExists("teacher3", $plan);

        (new TeacherFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getTeacherRepository()
        ))($plan);

        $this->assertEquals(0, ($this->schedulerContext->getTeacherRepository()->findOneBy(['id' => 1]))->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getTeacherRepository()->findOneBy(['id' => 2]))->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getTeacherRepository()->findOneBy(['id' => 3]))->getMapId());

    }
}