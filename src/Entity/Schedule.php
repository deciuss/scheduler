<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfGenerations;

    /**
     * @ORM\Column(type="integer")
     */
    private $softViolationFactor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (#%d)', $this->name, $this->id);
    }

    public function getNumberOfGenerations(): ?int
    {
        return $this->numberOfGenerations;
    }

    public function setNumberOfGenerations(int $numberOfGenerations): self
    {
        $this->numberOfGenerations = $numberOfGenerations;

        return $this;
    }

    public function getSoftViolationFactor(): ?int
    {
        return $this->softViolationFactor;
    }

    public function setSoftViolationFactor(int $softViolationFactor): self
    {
        $this->softViolationFactor = $softViolationFactor;

        return $this;
    }
}
