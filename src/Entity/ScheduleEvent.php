<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ScheduleEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleEventRepository::class)
 */
class ScheduleEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Schedule::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $schedule;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity=Timeslot::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $timeslot;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getTimeslot(): ?Timeslot
    {
        return $this->timeslot;
    }

    public function setTimeslot(?Timeslot $timeslot): self
    {
        $this->timeslot = $timeslot;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
