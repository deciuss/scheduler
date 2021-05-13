<?php

declare(strict_types=1);

namespace App\Tests\Mother;

use App\Entity\Teacher;

class TeacherMother
{
    public static function create(int $mapId) : Teacher
    {
        return (new Teacher())->setMapId($mapId);
    }
}