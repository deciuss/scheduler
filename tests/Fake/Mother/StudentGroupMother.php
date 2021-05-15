<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Tests\Fake\Mother\Entity\StudentGroup;

class StudentGroupMother
{
    public static function withMapIdWithParent(int $mapId, ?StudentGroup $parent = null) : StudentGroup
    {
        $studentGroup = (new StudentGroup())
            ->setMapId($mapId)
            ->setParent($parent);

        $parent instanceof StudentGroup && $parent->addChild($studentGroup);

        return $studentGroup;
    }

    public static function withId(int $id) : StudentGroup
    {
        return new StudentGroup($id);
    }

    public static function withMapId(int $mapId) : StudentGroup
    {
        return (new StudentGroup(hexdec(uniqid())))->setMapId($mapId);
    }

}