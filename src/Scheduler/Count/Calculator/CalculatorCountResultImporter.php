<?php

declare(strict_types=1);

namespace App\Scheduler\Count\Calculator;

use App\Entity\Schedule;
use App\Entity\ScheduleEvent;
use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\TimeslotRepository;
use App\Scheduler\Count\CountResultImporter;
use App\Scheduler\Count\ReportReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class CalculatorCountResultImporter implements CountResultImporter
{

    private string $calculatorOutputPath;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventRepository $eventRepository,
        private TimeslotRepository $timeslotRepository,
        private RoomRepository $roomRepository,
        private PlanRepository $planRepository,
        private DecoderInterface $decoder,
        private ReportReader $reportReader,
        ParameterBagInterface $parameterBag

    ) {
        $this->calculatorOutputPath = $parameterBag->get('scheduler.calculator.output_path');
    }

    public function __invoke(int $planId) : void
    {
        $report = $this->reportReader->getReportForPlan($plan = $this->planRepository->find($planId));

        $this->entityManager->persist(
            $schedule = (new Schedule())
                ->setPlan($plan)
                ->setCreatedAt(new \DateTime())
                ->setName(sprintf('Schedule for plan %d', $planId))
                ->setNumberOfGenerations($report->getGenerationNumber())
                ->setSoftViolationFactor($report->getOverallBestSoft())
        );

        foreach(
            $this->decoder->decode(
                file_get_contents(
                    sprintf("%s/%d", $this->calculatorOutputPath, $planId),
                ),
                'csv'
            ) as $mapId => $calculatorResult) {
                $this->entityManager->persist(
                    (new ScheduleEvent())
                        ->setSchedule($schedule)
                        ->setEvent($this->eventRepository->findOneByPlanAndMapId($planId, $mapId))
                        ->setTimeslot($this->timeslotRepository->findOneBy(['plan' => $planId, 'map_id' => $calculatorResult['timeslot']]))
                        ->setRoom($this->roomRepository->findOneBy(['plan' => $planId, 'map_id' => $calculatorResult['room']]))
                );
            }

        $this->entityManager->flush();
    }
}
