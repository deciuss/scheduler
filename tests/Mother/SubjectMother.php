<?php

declare(strict_types=1);

namespace App\Tests\Mother;

use App\Entity\Subject;

class SubjectMother
{
    public static function create(int $hours, int $blockSize) : Subject
    {
        return (new Subject())
            ->setHours($hours)
            ->setBlockSize($blockSize);
    }
}