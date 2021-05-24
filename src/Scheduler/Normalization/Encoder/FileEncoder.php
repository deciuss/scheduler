<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Encoder;

use App\Scheduler\Normalization\Encoder;

class FileEncoder implements Encoder
{
    public function encodeInt(int $data) : string
    {
        return $data . "\n\n";
    }

    public function encodeBoolMatrix(array $data) : string
    {
        return $this->encodeBoolMatrixRows($data) . "\n\n";
    }

    /**
     * @param int[]|int $data
     * @return string
     */
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