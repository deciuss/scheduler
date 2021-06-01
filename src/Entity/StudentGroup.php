<?php

declare(strict_types=1);

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

    /**
     * @ORM\ManyToOne(targetEntity=StudentGroup::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=StudentGroup::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $map_id;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->studentGroupsIntersected = new ArrayCollection();
        $this->children = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

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

    public function getMapId(): ?int
    {
        return $this->map_id;
    }

    public function setMapId(?int $map_id): self
    {
        $this->map_id = $map_id;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (#%d)', $this->name, $this->id);
    }
}
