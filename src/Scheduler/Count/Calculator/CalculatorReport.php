<?php

declare(strict_types=1);

namespace App\Scheduler\Count\Calculator;

use App\Scheduler\Count\Report;

class CalculatorReport implements Report
{
    public function __construct(
        private string $planStatus,
        private ?\DateTimeImmutable $createdAt = null,
        private ?int $generationNumber = null,
        private ?int $overallBestHard = null,
        private ?int $overallBestSoft = null,
        private ?int $currentBestHard = null,
        private ?int $currentBestSoft = null,
        private ?float $stepCurrentFactor = null
    ) {
    }

    public function getPlanStatus(): string
    {
        return $this->planStatus;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getGenerationNumber(): ?int
    {
        return $this->generationNumber;
    }

    public function getOverallBestHard(): ?int
    {
        return $this->overallBestHard;
    }

    public function getOverallBestSoft(): ?int
    {
        return $this->overallBestSoft;
    }

    public function getCurrentBestHard(): ?int
    {
        return $this->currentBestHard;
    }

    public function getCurrentBestSoft(): ?int
    {
        return $this->currentBestSoft;
    }

    public function getStepCurrentFactor(): ?float
    {
        return $this->stepCurrentFactor;
    }

    public function jsonSerialize(): array
    {
        return [
            'plan_status' => $this->planStatus,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'generation_number' => $this->generationNumber,
            'overall_best_hard' => $this->overallBestHard,
            'overall_best_soft' => $this->overallBestSoft,
            'current_best_hard' => $this->currentBestHard,
            'current_best_soft' => $this->currentBestSoft,
            'step_current_factor' => $this->stepCurrentFactor,
        ];
    }
}
