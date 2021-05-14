<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Tests\Stub\Entity\Teacher;

class TeacherMother
{
    public static function create(int $mapId = 0) : Teacher
    {
        return (new Teacher())->setMapId($mapId);
    }
}