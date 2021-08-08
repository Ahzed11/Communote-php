<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use App\Utils\TimestampableTrait;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener", "App\EntityListener\AuthorListener"])]
class Review
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "smallint")]
    #[Range(min:1, max: 5)]
    #[Groups(["note:read"])]
    private int $score;

    #[ORM\ManyToOne(targetEntity: Note::class, inversedBy: "reviews")]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull]
    private Note $note;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "reviews")]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getNote(): Note
    {
        return $this->note;
    }

    public function setNote(Note $note): self
    {
        $this->note = $note;

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
}
