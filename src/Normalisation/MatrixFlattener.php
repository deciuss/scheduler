<?php


namespace App\Normalisation;


class MatrixFlattener
{

    public function flatten(array $matrix) : string
    {
        return $this->doFlatten($matrix);
    }

    private function doFlatten(mixed &$data) : string
    {
        if (! is_array($data)) {
            return (string) (int) $data;
        }

        return array_reduce(
            $data,
            fn(string $carry, $item) => $carry . $this->doFlatten($item),
            ""
        );

    }

}