<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization;

class TruthMatrixGenerator
{
    public function generate(array $array1, array $array2, Condition ...$conditions): array
    {
        $matrix = [];
        for ($i = 0; $i < count($array1); ++$i) {
            for ($j = 0; $j < count($array2); ++$j) {
                $matrix[$i][$j] = array_reduce(
                    $conditions,
                    fn (bool $carry, Condition $condition) => $carry && $condition->check($array1[$i], $array2[$j]),
                    true
                );
            }
        }

        return $matrix;
    }
}
