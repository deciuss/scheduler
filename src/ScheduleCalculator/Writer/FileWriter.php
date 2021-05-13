<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Writer;

use App\ScheduleCalculator\Encoder;
use App\ScheduleCalculator\Writer;

class FileWriter implements Writer
{
    private Encoder $encoder;
    private string $outputFilePathName;

    public function __construct(Encoder $encoder, string $outputFilePathName)
    {
        $this->encoder = $encoder;
        $this->outputFilePathName = $outputFilePathName;
        touch($outputFilePathName);
    }

    public function appendInt(int $data): void
    {
        file_put_contents(
            $this->outputFilePathName,
            $this->encoder->encodeInt($data),
            FILE_APPEND
        );
    }

    public function appendBoolMatrix(array $data): void
    {
        file_put_contents(
            $this->outputFilePathName,
            $this->encoder->encodeBoolMatrix($data),
            FILE_APPEND
        );
    }

    public function appendIntOneToMany(array $data): void
    {
        file_put_contents(
            $this->outputFilePathName,
            $this->encoder->encodeIntOneToMany($data),
            FILE_APPEND
        );
    }

    public function appendIntArray(array $data): void
    {
        file_put_contents(
            $this->outputFilePathName,
            $this->encoder->encodeIntArray($data),
            FILE_APPEND
        );
    }

}