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
                return $this->flattenToString($matrix);
            case 'blocks':
                return $this->flattenToBlocks($matrix);
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

    private function flattenToBlocks(mixed &$data) : string
    {
        $result = count($data) . "\n";
        foreach ($data as $row) {
            $result .= count($row) . "\n";
            foreach ($row as $col) {
                $result .= $col . "\n";
            }
        }
        return $result;
    }

}