<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Subject;

class SubjectMother
{
    public static function create(int $hours = 1, int $blockSize = 1) : Subject
    {
        return (new Subject())
            ->setHours($hours)
            ->setBlockSize($blockSize);
    }
}