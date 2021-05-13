<?php

declare(strict_types=1);

namespace App\Tests\Stub\Entity;

class Teacher extends \App\Entity\Teacher
{
    private int $id;

    public function __construct()
    {
        $this->id = hexdec(uniqid());
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}