<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Tests\Fake\Mother\Entity\Teacher;

class TeacherMother
{
    public static function withMapId(int $mapId) : Teacher
    {
        return (new Teacher(hexdec(uniqid())))->setMapId($mapId);
    }

    public static function withId(int $id) : Teacher
    {
        return (new Teacher($id));
    }
}