<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 * @ORM\EntityListeners({"App\EntityListener\CreatedAtListener"})
 */
#[UniqueEntity(fields: ["code"], message: "This code is already used")]
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    #[NotBlank]
    #[Length(max: 127)]
    private string $title;

    /**
     * @ORM\Column(type="string", length=31, unique=true)
     */
    #[NotBlank]
    #[Length(max: 31)]
    private string $code;

    /**
     * @ORM\ManyToOne(targetEntity=Study::class, inversedBy="courses")
     */
    private ?Study $study;

    /**
     * @ORM\ManyToOne(targetEntity=Year::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     */
    #[NotNull]
    private Year $year;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="course", orphanRemoval=true)
     */
    private iterable $notes;

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
        $this->notes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title . " - " . $this->code;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getStudy(): ?Study
    {
        return $this->study;
    }

    public function setStudy(?Study $study): self
    {
        $this->study = $study;

        return $this;
    }

    public function getYear(): Year
    {
        return $this->year;
    }

    public function setYear(Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setCourse($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getCourse() === $this) {
                $note->setCourse(null);
            }
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
