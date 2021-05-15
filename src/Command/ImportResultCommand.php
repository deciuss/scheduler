<?php
namespace App\Command;

use App\Entity\Schedule;
use App\Entity\ScheduleEvent;
use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class ImportResultCommand extends Command
{

    protected static $defaultName = 'app:import';

    private EntityManagerInterface $entityManager;
    private DecoderInterface $decoder;
    private EventRepository $eventRepository;
    private TimeslotRepository $timeslotRepository;
    private RoomRepository $roomRepository;
    private PlanRepository $planRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository,
        TimeslotRepository $timeslotRepository,
        RoomRepository $roomRepository,
        PlanRepository $planRepository,
        DecoderInterface $decoder
    ) {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->roomRepository = $roomRepository;
        $this->planRepository = $planRepository;
        $this->decoder = new CsvEncoder();
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $calculatorFilePath = getcwd() . "/var/calculator/";
        $calculatorFileName = "output.csv";
        $calculatorFilePathName = $calculatorFilePath . $calculatorFileName;

        $calculatorResults = $this->decoder->decode(file_get_contents($calculatorFilePathName), 'csv');

        $schedule = new Schedule();
        $plan = $this->planRepository->findOneBy(['id'=>1]);
        $schedule->setPlan($plan);
        $schedule->setCreatedAt(new \DateTime());
        $this->entityManager->persist($schedule);

        foreach($calculatorResults as $index => $calculatorResult) {
            $scheduleEvent = new ScheduleEvent();
            $scheduleEvent->setSchedule($schedule);
//            $scheduleEvent->setEvent($this->eventRepository->findOneBy(['plan' => $plan, 'mapId' => $index]));
            $scheduleEvent->setEvent($this->eventRepository->findOneBy([ 'id' => $index + 1]));
            $scheduleEvent->setTimeslot($this->timeslotRepository->findOneBy(['plan' => $plan, 'map_id' => $calculatorResult['timeslot']]));
            $scheduleEvent->setRoom($this->roomRepository->findOneBy(['plan' => $plan, 'map_id' => $calculatorResult['room']]));
            $this->entityManager->persist($scheduleEvent);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}