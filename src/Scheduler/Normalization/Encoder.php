<?php

namespace App\Scheduler\Normalization;

interface Encoder
{
    public function encodeInt(int $data): string;

    public function encodeBoolMatrix(array $data): string;

    public function encodeIntOneToMany(array $data): string;

    public function encodeIntArray(array $data): string;
}
