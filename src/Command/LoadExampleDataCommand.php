<?php
namespace App\Command;

use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadExampleDataCommand extends Command
{

    protected static $defaultName = 'dev:example:load';

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Loads example plan.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = [
            'src/Resources/example/loader/user.yml',
            'src/Resources/example/loader/plan.yml',
            'src/Resources/example/loader/teacher.yml',
            'src/Resources/example/loader/student_group.yml',
            'src/Resources/example/loader/feature.yml',
            'src/Resources/example/loader/room.yml',
            'src/Resources/example/loader/subject.yml',
            'src/Resources/example/loader/timeslot.yml',
        ];

        $loader = $this->container->get('fidry_alice_data_fixtures.loader.doctrine');

        $loader->load($files, [], [], PurgeMode::createNoPurgeMode());

        return Command::SUCCESS;
    }
}
