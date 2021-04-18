<?php


namespace App\Normalisation;


class TruthMatrixGenerator
{

    public function generate(array $array1, array $array2, Condition ...$conditions) : array
    {
        $matrix = [];
        for ($i = 0; $i < count($array1); $i++) {
            for ($j = 0; $j < count($array2); $j++) {
//                var_dump('Processing: ' . $i . ' ' . $j);
                $matrix[$i][$j] = array_reduce(
                    $conditions,
                    fn(bool $carry, Condition $condition) => $carry && $condition->check($array1[$i], $array2[$j]),
                    True
                );
            }
        }
        return $matrix;
    }

}