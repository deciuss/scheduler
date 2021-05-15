<?php

declare(strict_types=1);

namespace App\Tests\Mother;

use App\Tests\Mother\Entity\Feature;

class FeatureMother
{
    public static function withId(int $id) : Feature
    {
        return new Feature($id);
    }
}