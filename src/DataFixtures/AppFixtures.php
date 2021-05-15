<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Psr\Container\ContainerInterface;

class AppFixtures extends Fixture
{

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $files = [
            'src/Resources/fixtures/user.yml',
            'src/Resources/fixtures/plan.yml',
            'src/Resources/fixtures/teacher.yml',
            'src/Resources/fixtures/student_group.yml',
            'src/Resources/fixtures/feature.yml',
            'src/Resources/fixtures/room.yml',
            'src/Resources/fixtures/subject.yml',
            'src/Resources/fixtures/timeslot.yml',
        ];

        $loader = $this->container->get('fidry_alice_data_fixtures.loader.doctrine');
        $loader->load($files, [], [], PurgeMode::createNoPurgeMode());
    }
}