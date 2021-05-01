<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @ORM\EntityListeners({
 *     "App\EntityListener\CreatedAtListener",
 *     "App\EntityListener\AuthorListener",
 *     "App\EntityListener\SlugListener"
 *     })
 */
#[UniqueEntity(fields: ["slug"], message: "This slug is already used")]
class Note
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
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    #[NotBlank]
    #[Length(max: 127)]
    private string $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    #[NotBlank]
    #[Length(max: 2000)]
    private string $description;

    /**
     * @ORM\Column(type="string", length=127, unique=true)
     */
    private string $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    #[NotNull]
    private Course $course;

    /**
     * @ORM\OneToOne(targetEntity=NoteFile::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private NoteFile $noteFile;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="note", orphanRemoval=true)
     */
    private iterable $comments;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="note", orphanRemoval=true)
     */
    private iterable $reports;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="note", orphanRemoval=true)
     */
    private iterable $reviews;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[NotNull]
    private $wroteAt;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getNoteFile(): NoteFile
    {
        return $this->noteFile;
    }

    public function setNoteFile(NoteFile $noteFile): self
    {
        $this->noteFile = $noteFile;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setNote($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getNote() === $this) {
                $comment->setNote(null);
            }
        }

        return $this;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setNote($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getNote() === $this) {
                $report->setNote(null);
            }
        }

        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setNote($this);
        }

        return $this;
    }

    public function getAverageScore(): float
    {
        $sum = 0.0;
        foreach ($this->reviews as $review) {
            $sum += $review->getScore();
        }

        return count($this->reviews) > 0 ? $sum / count($this->reviews) : 0;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getNote() === $this) {
                $review->setNote(null);
            }
        }

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

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

    public function getWroteAt(): ?DateTimeInterface
    {
        return $this->wroteAt;
    }

    public function setWroteAt(DateTimeInterface $wrote_at): self
    {
        $this->wroteAt = $wrote_at;

        return $this;
    }

    public function getPath() : string
    {
        $path = $this->course->getStudy()->getFaculty()->getSchool()->getTitle() . '/' .
            $this->course->getStudy()->getFaculty()->getTitle() . '/' .
            $this->course->getStudy()->getTitle() . '/' .
            $this->course->getTitle();
        return str_replace(' ', '-', $path);
    }
}
