<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
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
     * @ORM\Column(type="text")
     */
    #[NotBlank]
    #[Length(max: 800)]
    private string $description;

    /**
     * @ORM\Column(type="string", length=127)
     */
    #[NotBlank]
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
    #[NotNull]
    private NoteFile $file;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="Note", orphanRemoval=true)
     */
    private iterable $comments;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="Note", orphanRemoval=true)
     */
    private iterable $reports;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="note", orphanRemoval=true)
     */
    private iterable $reviews;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getFile(): NoteFile
    {
        return $this->file;
    }

    public function setFile(NoteFile $file): self
    {
        $this->file = $file;

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
}
