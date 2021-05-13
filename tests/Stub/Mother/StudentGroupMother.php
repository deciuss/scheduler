<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Tests\Stub\Entity\StudentGroup;

class StudentGroupMother
{
    public static function create(int $mapId, ?StudentGroup $parent = null) : StudentGroup
    {
        $studentGroup = (new StudentGroup())
            ->setMapId($mapId)
            ->setParent($parent);

        $parent instanceof StudentGroup && $parent->addChild($studentGroup);

        return $studentGroup;
    }
}