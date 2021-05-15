<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\TimeslotRepository;
use App\Scheduler\Infrastructure\Handler\CalculateScheduleHandler;
use App\Tests\Context\SchedulerContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected SchedulerContext $schedulerContext;

    protected function setUp() : void
    {
        parent::bootKernel();

        $this->schedulerContext = new SchedulerContext(
            self::$container->get(EntityManagerInterface::class),
            self::$container->get(PlanRepository::class),
            self::$container->get(SubjectRepository::class),
            self::$container->get(EventRepository::class),
            self::$container->get(RoomRepository::class),
            self::$container->get(StudentGroupRepository::class),
            self::$container->get(TeacherRepository::class),
            self::$container->get(TimeslotRepository::class),
            self::$container->get(CalculateScheduleHandler::class),
        );
    }

}
