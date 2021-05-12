<?php
namespace App\Command;

use App\ScheduleCalculator\Message\CalculateSchedule;
use App\Repository\PlanRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DispatchMessageCommand extends Command
{

    protected static $defaultName = 'app:debug:dispatch-command';

    private MessageBusInterface $messageBus;
    private PlanRepository $planRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        PlanRepository $planRepository
    ) {
        $this->messageBus = $messageBus;
        $this->planRepository = $planRepository;
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $plan = $this->planRepository->findOneBy(['id' => 1]);

        $this->messageBus->dispatch(new CalculateSchedule($plan));

        return Command::SUCCESS;
    }
}