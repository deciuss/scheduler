<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Tests\Fake\Mother\Entity\StudentGroup;

class StudentGroupMother
{

    public static function withId(int $id) : StudentGroup
    {
        return new StudentGroup($id);
    }

    public static function withMapId(int $mapId) : StudentGroup
    {
        return (new StudentGroup(hexdec(uniqid())))->setMapId($mapId);
    }

    public static function withCardinality(int $cardinality) : StudentGroup
    {
        return (new StudentGroup(hexdec(uniqid())))->setCardinality($cardinality);
    }

}