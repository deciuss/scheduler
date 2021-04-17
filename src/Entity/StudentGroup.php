<?php

namespace App\Entity;

use App\Repository\StudentGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentGroupRepository::class)
 */
class StudentGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $cardinality;

    /**
     * @ORM\ManyToMany(targetEntity=StudentGroup::class)
     */
    private $studentGroupsIntersected;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->studentGroupsIntersected = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCardinality(): ?int
    {
        return $this->cardinality;
    }

    public function setCardinality(int $cardinality): self
    {
        $this->cardinality = $cardinality;

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->setStudentGroup($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getStudentGroup() === $this) {
                $subject->setStudentGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getStudentGroupsIntersected(): Collection
    {
        return $this->studentGroupsIntersected;
    }

    public function addStudentGroupsIntersected(self $studentGroupsIntersected): self
    {
        if (!$this->studentGroupsIntersected->contains($studentGroupsIntersected)) {
            $this->studentGroupsIntersected[] = $studentGroupsIntersected;
        }

        return $this;
    }

    public function removeStudentGroupsIntersected(self $studentGroupsIntersected): self
    {
        $this->studentGroupsIntersected->removeElement($studentGroupsIntersected);

        return $this;
    }

}
