<?php

declare(strict_types=1);

namespace App\Tests\Stub\Entity;

class StudentGroup extends \App\Entity\StudentGroup
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