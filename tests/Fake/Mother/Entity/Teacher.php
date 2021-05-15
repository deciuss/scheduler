<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother\Entity;

class Teacher extends \App\Entity\Teacher
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}