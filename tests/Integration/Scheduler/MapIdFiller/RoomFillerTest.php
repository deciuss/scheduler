<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\MapIdFiller;

use App\Scheduler\Normalization\MapIdFiller\RoomFiller;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Normalization\MapIdFiller\RoomFiller
 */
class RoomFillerTest extends IntegrationTestCase
{
    public function test_if_fills_room_map_ids() : void
    {
        $plan = $this->schedulerContext->givenPlanExists("plan");

        $this->schedulerContext->givenRoomExists("room1", $plan);
        $this->schedulerContext->givenRoomExists("room2", $plan);
        $this->schedulerContext->givenRoomExists("room3", $plan);

        (new RoomFiller(
            $this->schedulerContext->getEntityManager(),
            $this->schedulerContext->getRoomRepository()
        ))($plan);

        $this->assertEquals(0, ($this->schedulerContext->getRoomRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 0))[0]->getMapId());
        $this->assertEquals(1, ($this->schedulerContext->getRoomRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 1))[0]->getMapId());
        $this->assertEquals(2, ($this->schedulerContext->getRoomRepository()->findBy(['plan' => $plan], ['id' => 'ASC'], 1, 2))[0]->getMapId());
    }
}