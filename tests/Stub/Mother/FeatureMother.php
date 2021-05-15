<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Tests\Stub\Entity\Feature;

class FeatureMother
{
    public static function withId(int $id) : Feature
    {
        return new Feature($id);
    }
}