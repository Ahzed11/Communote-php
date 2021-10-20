<?php

namespace App\Entity;

use App\Repository\NoteFileRepository;
use App\Utils\TimestampableTrait;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: NoteFileRepository::class)]
#[ORM\EntityListeners(["App\EntityListener\TimestampListener"])]
class NoteFile
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    #[NotBlank]
    private string $fileName;

    #[ORM\Column(type: "string", length: 255)]
    #[NotBlank]
    private string $originalFilename;

    #[ORM\Column(type: "string", length: 255)]
    #[NotBlank]
    private string $mimeType;

    public function __toString(): string
    {
        return $this->fileName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
