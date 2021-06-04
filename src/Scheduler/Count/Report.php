<?php

declare(strict_types=1);

namespace App\Scheduler\Count;

interface Report extends \JsonSerializable
{
    public function getPlanStatus(): string;

    public function getCreatedAt(): ?\DateTimeImmutable;

    public function getGenerationNumber(): ?int;

    public function getOverallBestHard(): ?int;

    public function getOverallBestSoft(): ?int;

    public function getCurrentBestHard(): ?int;

    public function getCurrentBestSoft(): ?int;

    public function getStepCurrentFactor(): ?float;
}
