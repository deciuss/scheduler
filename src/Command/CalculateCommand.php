<?php
namespace App\Command;

use App\Scheduler\Message\CalculateSchedule;
use App\Repository\PlanRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CalculateCommand extends Command
{

    protected static $defaultName = 'app:calculate';

    private MessageBusInterface $messageBus;
    private PlanRepository $planRepository;
    private string $calculatorDataPath;

    public function __construct(
        MessageBusInterface $messageBus,
        PlanRepository $planRepository,
        ParameterBagInterface $parameterBag
    ) {
        $this->messageBus = $messageBus;
        $this->planRepository = $planRepository;
        $this->calculatorDataPath = $parameterBag->get("scheduler.calculator.data_path");
        parent::__construct();
    }


    protected function configure()
    {
        $this->addArgument('plan_id', InputArgument::REQUIRED, 'Plan to be calculated.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $plan = $this->planRepository->findOneBy(['id' => $input->getArgument('plan_id')]);

        try {
            $this->messageBus->dispatch(new CalculateSchedule($plan));
        } catch(\Exception $e) {
            $output->writeln(sprintf("During plan calculation error has occured: %s", $e->getMessage()));
        }

        $output->writeln("Plan generation successfull");
        $output->writeln(sprintf("Calculator file: %s/%d", $this->calculatorDataPath, $plan->getId()));

        return Command::SUCCESS;
    }
}