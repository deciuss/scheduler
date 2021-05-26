<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\Normalization\MapIdFiller;

use App\Scheduler\Normalization\MapIdFiller\TimeslotFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Normalization\MapIdFiller\TimeslotFiller
 */
class TimeslotFillerTest extends IntegrationTestCase
{
    public function test_if_fills_room_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");

        $this->schedulerContext->givenTimeslotExists($plan);
        $this->schedulerContext->givenTimeslotExists($plan);
        $this->schedulerContext->givenTimeslotExists($plan);

        (new TimeslotFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getTimeslotRepository()
        ))($plan);

        $this->schedulerContext->getEntityManager()->clear();

        $this->assertEquals(0, ($this->schedulerContext->getTimeslotRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getTimeslotRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getTimeslotRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}