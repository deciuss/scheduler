<?php
namespace App\Command;

use App\Normalisation\Generator\EventRoomFit;
use App\Normalisation\Generator\EventTimeslotShare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommand extends Command
{

    protected static $defaultName = 'app:calculate';

    private EventTimeslotShare $eventTimeslotShare;
    private EventRoomFit $eventRoomFit;

    public function __construct(EventTimeslotShare $eventTimeslotShare, EventRoomFit $eventRoomFit)
    {
        $this->eventTimeslotShare = $eventTimeslotShare;
        $this->eventRoomFit = $eventRoomFit;
        parent::__construct();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '2048M');

//        $matrix = $this->eventTimeslotShare->generate();

        $matrix = $this->eventRoomFit->generate();

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                echo $matrix[$i][$j] == true ? 1 : 0;
            }
            echo "\n";
        }

        return Command::SUCCESS;
    }
}