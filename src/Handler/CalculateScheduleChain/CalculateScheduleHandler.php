<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class CalculateScheduleHandler extends ChainedHandler implements MessageHandlerInterface
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
                PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED
            ]
        );
    }

    public function __construct(
        NormalizedDataGenerationHandler $normalizedDataGenerationHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($normalizedDataGenerationHandler, $logger);
        $this->planRepository = $planRepository;
    }

    public function __invoke(CalculateSchedule $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->invokeNextHandler($message);
            return;
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));

        // @todo

        $this->logger->info(sprintf('%s started finished message: %s %s', get_class($this), get_class($message), json_encode($message)));

    }
}