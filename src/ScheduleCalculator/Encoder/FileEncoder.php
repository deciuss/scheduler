<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Encoder;

use App\ScheduleCalculator\Encoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class FileEncoder implements Encoder
{

    private EncoderInterface $encoder;

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encodeInt(int $data) : string
    {
        return $data . "\n\n";
    }

    public function encodeBoolMatrix(array $data) : string
    {
        return $this->encodeBoolMatrixRows($data) . "\n\n";
    }

    private function encodeBoolMatrixRows(mixed $data) : string
    {
        if (! is_array($data)) {
            return (string) (int) $data;
        }

        return array_reduce(
            $data,
            fn(string $carry, $item) => $carry . $this->encodeBoolMatrixRows($item),
            ""
        );
    }

    public function encodeIntOneToMany(array $data) : string
    {
        $result = "";
        foreach ($data as $row) {
            $result .= count($row) . "\n";
            foreach ($row as $col) {
                $result .= $col . "\n";
            }
        }
        return $result . "\n";
    }

    public function encodeIntArray(array $data) : string
    {
        $result = "";
        foreach ($data as $col) {
            $result .= $col . "\n";
        }
        return $result . "\n";
    }

}