<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\ResultImportHandler;

use App\Scheduler\Count\CountResultImporter;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler;
use App\Scheduler\Handler\CalculateScheduleChain\ResultImportHandler;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;

class DefaultResultImportHandler extends ChainHandlerAbstract implements ResultImportHandler
{
    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private CountResultImporter $calculatorCountResultImporter,
        CalculationHandler $calculationHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($calculationHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        return $this->planStatusStateMachine->can($message->getPlanId(), 'result_import_starting');
    }

    public function handle(Message $message): void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(), 'result_import_starting');

        try {
            ($this->calculatorCountResultImporter)($message->getPlanId());
            $this->planStatusStateMachine->apply($message->getPlanId(), 'result_import_finishing');
        } catch (\Exception $e) {
            $this->planStatusStateMachine->apply($message->getPlanId(), 'result_import_error_marking');
            throw $e;
        }
    }
}
