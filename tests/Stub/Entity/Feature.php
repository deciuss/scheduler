<?php

declare(strict_types=1);

namespace App\Tests\Stub\Entity;

class Feature extends \App\Entity\Feature
{
    private int $id;

    public function __construct()
    {
        $this->id = hexdec(uniqid());
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}