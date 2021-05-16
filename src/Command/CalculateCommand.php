<?php
namespace App\Command;

use App\Scheduler\Scheduler;
use App\Scheduler\UI\Exception\PlanDoesNotExistException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CalculateCommand extends Command
{
    protected static $defaultName = 'app:calculate';

    private Scheduler $scheduler;
    private string $calculatorDataPath;

    public function __construct(
        Scheduler $scheduler,
        ParameterBagInterface $parameterBag
    ) {
        $this->scheduler = $scheduler;
        $this->calculatorDataPath = $parameterBag->get("scheduler.calculator.data_path");
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Calculates schedule for given plan_id.");
        $this->addArgument('plan_id', InputArgument::REQUIRED, 'Plan to be calculated.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $planId = $input->getArgument('plan_id');

        try {
            $this->scheduler->calculate($planId);
        } catch (PlanDoesNotExistException $e) {
            $output->writeln($e->getMessage());
        } catch (\Exception $e) {
            $output->writeln(sprintf("During plan calculation error has occurred: %s", $e->getMessage()));
        }

        $output->writeln(
            sprintf(
                "Plan generation successfull. Calculator file: %s/%d",
                $this->calculatorDataPath,
                $planId
            )
        );

        return Command::SUCCESS;
    }
}