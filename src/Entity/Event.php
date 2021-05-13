<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $map_id;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMapId(): ?int
    {
        return $this->map_id;
    }

    public function setMapId(?int $map_id): self
    {
        $this->map_id = $map_id;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
