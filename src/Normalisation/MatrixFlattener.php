<?php


namespace App\Normalisation;


use Symfony\Component\Serializer\Encoder\EncoderInterface;

class MatrixFlattener
{

    private EncoderInterface $encoder;

    public function __construct(EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function flatten(array $matrix, $mode) : string
    {
        switch ($mode) {
            case 'string':
                return $this->flattenToString($matrix) . "\n";
            case 'oneToMany':
                return $this->flattenToOneToMany($matrix);
            case 'array':
                return $this->flattenToArray($matrix);
            default:
                throw new \RuntimeException('Invalid flatten mode');
        }
    }

    private function flattenToString(mixed &$data) : string
    {
        if (! is_array($data)) {
            return (string) (int) $data;
        }

        return array_reduce(
            $data,
            fn(string $carry, $item) => $carry . $this->flattenToString($item),
            ""
        );

    }

    private function flattenToOneToMany(mixed &$data) : string
    {
        $result = "";
        foreach ($data as $row) {
            $result .= count($row) . "\n";
            foreach ($row as $col) {
                $result .= $col . "\n";
            }
        }
        return $result;
    }

    private function flattenToArray(mixed &$data) : string
    {
        $result = "";
        foreach ($data as $col) {
            $result .= $col . "\n";
        }
        return $result;
    }

}