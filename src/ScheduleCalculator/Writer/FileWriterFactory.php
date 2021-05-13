<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Writer;

use App\ScheduleCalculator\Encoder;
use App\ScheduleCalculator\Encoder\FileEncoder;
use App\ScheduleCalculator\Writer;
use App\ScheduleCalculator\WriterFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileWriterFactory implements WriterFactory
{
    private Encoder $encoder;
    private string $outputFilePath;

    public function __construct(ParameterBagInterface $parameterBag, FileEncoder $encoder)
    {
        $this->outputFilePath = $parameterBag->get('scheduler.calculator.data_path');
        $this->encoder = $encoder;
    }

    public function create(string $dataIdentifier): Writer
    {
        return new FileWriter(
            $this->encoder,
            sprintf("%s/%s", $this->outputFilePath, $dataIdentifier)
        );
    }
}