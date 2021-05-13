<?php

declare(strict_types=1);

namespace App\ScheduleCalculator;

use Symfony\Component\Serializer\Encoder\EncoderInterface;

class DataEncoder
{

    private EncoderInterface $encoder;

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param int|array $data
     * @param string $mode
     * @return string
     */
    public function encode($data, string $mode) : string
    {
        switch ($mode) {
            case 'int':
                return $this->encodeInt($data);
            case 'boolMatrix':
                return $this->encodeBoolMatrix($data);
            case 'intOneToMany':
                return $this->encodeIntOneToMany($data);
            case 'intArray':
                return $this->encodeIntArray($data);
            default:
                throw new \RuntimeException('Invalid encoder mode');
        }
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