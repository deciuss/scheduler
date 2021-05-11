<?php
namespace App\Command;

use App\Normalisation\EventHydrator;
use App\Repository\PlanRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HydrateEventsCommand extends Command
{

    protected static $defaultName = 'app:events:hydrate';

    private EventHydrator $eventHydrator;
    private PlanRepository $planRepository;

    public function __construct(EventHydrator $eventHydrator, PlanRepository $planRepository)
    {
        $this->eventHydrator = $eventHydrator;
        $this->planRepository = $planRepository;
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $p = $this->planRepository->findOneBy(['id' => 3]);
        $this->eventHydrator->hydrate($p);
        return Command::SUCCESS;
    }
}