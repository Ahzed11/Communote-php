<?php


namespace App\Utils;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $updatedAt;


    public function getCreatedAt() : DateTimeInterface {
        return $this->createdAt;
    }

    public function getUpdatedAt() : DateTimeInterface {
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt) : self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt) : self {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}