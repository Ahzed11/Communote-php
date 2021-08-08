<?php

namespace App\Entity;

use App\Repository\SchoolRepository;
use App\Utils\TimestampableTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener"])]
class School
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "string", length: 127)]
    #[NotBlank]
    #[Length(max: 127)]
    private string $title;

    #[ORM\OneToMany(mappedBy: "school", targetEntity: Faculty::class)]
    private iterable $faculties;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "schools")]
    private iterable $students;

    public function __construct()
    {
        $this->faculties = new ArrayCollection();
        $this->students = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFaculties(): Collection
    {
        return $this->faculties;
    }

    public function addFaculty(Faculty $faculty): self
    {
        if (!$this->faculties->contains($faculty)) {
            $this->faculties[] = $faculty;
            $faculty->setSchool($this);
        }

        return $this;
    }

    public function removeFaculty(Faculty $faculty): self
    {
        if ($this->faculties->removeElement($faculty)) {
            // set the owning side to null (unless already changed)
            if ($faculty->getSchool() === $this) {
                $faculty->setSchool(null);
            }
        }

        return $this;
    }

    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->addSchool($this);
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        if ($this->students->removeElement($student)) {
            $student->removeSchool($this);
        }

        return $this;
    }
}
