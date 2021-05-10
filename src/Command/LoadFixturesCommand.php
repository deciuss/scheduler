<?php
namespace App\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{

    protected static $defaultName = 'app:load-fixtures';

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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

        $loader->load($files);

        return Command::SUCCESS;
    }
}