<?php
namespace App\Command;

use App\Normalisation\Generator\EventBlock;
use App\Normalisation\Generator\EventRoomFit;
use App\Normalisation\Generator\EventTimeslotShare;
use App\Normalisation\Generator\TimeslotNeighborNext;
use App\Normalisation\Generator\EventGroup;
use App\Normalisation\Generator;
use App\Normalisation\MatrixFlattener;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\TimeslotRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommand extends Command
{

    protected static $defaultName = 'app:calculate';


    /**
     * @var Generator[]
     */
    private array $generators = [];

    private EventRepository $eventRepository;
    private RoomRepository $roomRepository;
    private TimeslotRepository $timeslotRepository;
    private StudentGroupRepository $studentGroupRepository;

    private MatrixFlattener $matrixFlattener;

    public function __construct(
        EventTimeslotShare $eventTimeslotShare,
        EventRoomFit $eventRoomFit,
        EventBlock $eventBlock,
        TimeslotNeighborNext $timeslotNeighborNext,
        EventGroup $eventGroup,
        MatrixFlattener $matrixFlattener,
        EventRepository $eventRepository,
        RoomRepository $roomRepository,
        TimeslotRepository $timeslotRepository,
        StudentGroupRepository $studentGroupRepository
    ) {
        $this->generators[] = $eventBlock;
        $this->generators[] = $eventTimeslotShare;
        $this->generators[] = $eventRoomFit;
        $this->generators[] = $timeslotNeighborNext;
        $this->generators[] = $eventGroup;

        $this->matrixFlattener = $matrixFlattener;

        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->studentGroupRepository = $studentGroupRepository;

        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '2048M');

        $calculatorFilePath = getcwd() . "/var/calculator/";
        $calculatorFileName = "calculator_file";
        $calculatorFilePathName = $calculatorFilePath . $calculatorFileName;

        touch($calculatorFilePathName);

        file_put_contents($calculatorFilePathName, $this->eventRepository->count([]) . "\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->roomRepository->count([]) . "\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->timeslotRepository->count([]) . "\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->studentGroupRepository->count([]) . "\n",FILE_APPEND);

        foreach ($this->generators as $generator) {
            file_put_contents(
                $calculatorFilePathName,
                $this->matrixFlattener->flatten($generator->generate(), $generator->getMode()) . "\n",
                FILE_APPEND
            );
        }

        return Command::SUCCESS;
    }
}