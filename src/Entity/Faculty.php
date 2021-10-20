<?php

namespace App\Entity;

use App\Repository\FacultyRepository;
use App\Utils\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: FacultyRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener"])]
class Faculty
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

    #[ORM\OneToMany(mappedBy: "faculty", targetEntity: Study::class, orphanRemoval: true)]
    private iterable $studies;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: "faculties")]
    #[ORM\JoinColumn(nullable: false)]
    private School $school;

    public function __construct()
    {
        $this->studies = new ArrayCollection();
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

    public function getStudies(): Collection
    {
        return $this->studies;
    }

    public function addStudy(Study $study): self
    {
        if (!$this->studies->contains($study)) {
            $this->studies[] = $study;
            $study->setFaculty($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->removeElement($study)) {
            // set the owning side to null (unless already changed)
            if ($study->getFaculty() === $this) {
                $study->setFaculty(null);
            }
        }

        return $this;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function setSchool(School $school): self
    {
        $this->school = $school;

        return $this;
    }
}
