<?php

namespace App\Entity;

use App\Repository\CommentProgRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentProgRepository::class)]
class CommentProg
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Programme $progComment = null;

    #[ORM\ManyToOne(inversedBy: 'commentprogs')]
    #[ORM\JoinColumn(name: 'user_cin', referencedColumnName: 'cin')]
    private ?User $usercmnp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProgComment(): ?Programme
    {
        return $this->progComment;
    }

    public function setProgComment(?Programme $progComment): static
    {
        $this->progComment = $progComment;

        return $this;
    }

    public function getUsercmnp(): ?User
    {
        return $this->usercmnp;
    }

    public function setUsercmnp(?User $usercmnp): static
    {
        $this->usercmnp = $usercmnp;

        return $this;
    }
}
