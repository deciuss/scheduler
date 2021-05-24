<?php

declare(strict_types=1);

namespace App\Scheduler\Writer;

use App\Scheduler\Normalization\Encoder;
use App\Scheduler\Encoder\FileEncoder;
use App\Scheduler\Writer;
use App\Scheduler\WriterFactory;
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