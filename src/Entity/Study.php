<?php

namespace App\Entity;

use App\Repository\StudyRepository;
use App\Utils\TimestampableTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: StudyRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener"])]
class Study
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 127)]
    #[NotBlank]
    #[Length(max: 127)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Faculty::class, inversedBy: "studies")]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull]
    private Faculty $faculty;

    #[ORM\OneToMany(mappedBy: "study", targetEntity: Course::class)]
    private iterable $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): int
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

    public function getFaculty(): Faculty
    {
        return $this->faculty;
    }

    public function setFaculty(Faculty $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setStudy($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getStudy() === $this) {
                $course->setStudy(null);
            }
        }

        return $this;
    }
}
