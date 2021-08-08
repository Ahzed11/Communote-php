<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use App\Utils\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener", "App\EntityListener\AuthorListener"])]
class Report
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "text")]
    #[NotBlank]
    #[Length(min: 50, max: 1200)]
    private string $body;

    #[ORM\ManyToOne(targetEntity: Note::class, inversedBy: "reports")]
    #[ORM\JoinColumn(nullable: false)]
    private Note $note;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "reports")]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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
