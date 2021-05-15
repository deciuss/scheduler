<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Tests\Fake\Mother\Entity\Feature;

class FeatureMother
{
    public static function withId(int $id) : Feature
    {
        return new Feature($id);
    }
}