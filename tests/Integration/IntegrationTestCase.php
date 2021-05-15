<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Repository\TeacherRepository;
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
            self::$container->get(TeacherRepository::class)
        );
    }

}
