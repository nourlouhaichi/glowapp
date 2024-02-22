<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecr = null;

    #[Assert\NotBlank(message:"content required ")]
    #[Assert\Length(min:3,minMessage:"too short ")]
    #[ORM\Column(length: 255)]
    private ?string $contenue = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Publication $publication = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecr(): ?\DateTimeInterface
    {
        return $this->datecr;
    }

    public function setDatecr(\DateTimeInterface $datecr): static
    {
        $this->datecr = $datecr;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): static
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): static
    {
        $this->publication = $publication;

        return $this;
    }
}
