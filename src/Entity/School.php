<?php

namespace App\Entity;

use App\Repository\SchoolRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 * @ORM\EntityListeners({"App\EntityListener\CreatedAtListener"})
 */
class School
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    #[NotBlank]
    #[Length(max: 127)]
    private string $title;

    /**
     * @ORM\OneToMany(targetEntity=Faculty::class, mappedBy="school")
     */
    private iterable $faculties;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="schools")
     */
    private iterable $students;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
