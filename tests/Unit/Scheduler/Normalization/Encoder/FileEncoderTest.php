<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Normalization\Encoder;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Encoder\FileEncoder;

/**
 * @covers \App\Scheduler\Normalization\Encoder\FileEncoder
 */
class FileEncoderTest extends TestCase
{
    public function test_encodes_integer(): void
    {
        $integer = 123;

        $actualEncodedIntegerValue = (new FileEncoder())->encodeInt($integer);

        $this->assertEquals("123\n\n", $actualEncodedIntegerValue);
    }

    public function test_encodes_bool_matrix(): void
    {
        $matrix = [
            [true, false, true, true],
            [true, true, false, false],
            [false, true, true, false]
        ];

        $actualEncodedBoolMatrix = (new FileEncoder())->encodeBoolMatrix($matrix);

        $this->assertEquals("101111000110\n\n", $actualEncodedBoolMatrix);
    }

    public function test_encodes_int_one_to_many(): void
    {
        $matrix = [
            [1, 2, 3],
            [4],
            [],
            [5, 6, 7, 8]
        ];

        $actualEncodedBoolMatrix = (new FileEncoder())->encodeIntOneToMany($matrix);

        $this->assertEquals(
            "3\n" . "1\n2\n3\n"
            . "1\n" . "4\n"
            . "0\n"
            . "4\n" . "5\n6\n7\n8\n"
            . "\n",
            $actualEncodedBoolMatrix
        );
    }

    public function test_encodes_int_array(): void
    {
        $array = [1, 2, 3, 4, 5];

        $actualEncodedIntArray = (new FileEncoder())->encodeIntArray($array);

        $this->assertEquals(
            "1\n2\n3\n4\n5\n\n",
            $actualEncodedIntArray
        );
    }

}