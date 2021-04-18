<?php
namespace App\Command;

use App\Normalisation\EventHydrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HydrateEventsCommand extends Command
{

    protected static $defaultName = 'app:events:hydrate';

    private EventHydrator $eventHydrator;

    public function __construct(EventHydrator $eventHydrator)
    {
        $this->eventHydrator = $eventHydrator;
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $this->eventHydrator->truncate();
        $this->eventHydrator->hydrate();
        return Command::SUCCESS;
    }
}