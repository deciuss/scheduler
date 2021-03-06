<?php

declare(strict_types=1);

namespace App\Entity;

use App\DBAL\PlanStatus;
use App\Repository\PlanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="plan_status")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isWeekly = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isLocked(): bool
    {
        return PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION !== $this->status;
    }

    public function getIsWeekly(): ?bool
    {
        return $this->isWeekly;
    }

    public function setIsWeekly(bool $isWeekly): self
    {
        $this->isWeekly = $isWeekly;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (#%d)', $this->name, $this->id);
    }
}
