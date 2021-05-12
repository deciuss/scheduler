<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class NormalisationErrorHandler extends ChainedHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_NORMALISATION_ERROR
            ]
        );
    }

    public function __construct(
        PlanRepository $planRepository,
        LoggerInterface $logger
    ) {
        parent::__construct(null, $logger);
        $this->planRepository = $planRepository;
    }


    public function __invoke(CalculateSchedule $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->invokeNextHandler($message);
            return;
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
        throw new \RuntimeException(sprintf("Normalisation for plan %d failed.", $message->getPlanId()));
        $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
    }
}