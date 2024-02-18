<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"title required ")]
    #[Assert\Length(max:10,maxMessage:"title too long ")]
    private ?string $titre_p = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"title required ")]
    private ?string $type_p = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"title required ")]
    private ?string $contenue_p = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecr_p = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'publication')]
    private Collection $comments;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreP(): ?string
    {
        return $this->titre_p;
    }

    public function setTitreP(string $titre_p): static
    {
        $this->titre_p = $titre_p;

        return $this;
    }

    public function getTypeP(): ?string
    {
        return $this->type_p;
    }

    public function setTypeP(string $type_p): static
    {
        $this->type_p = $type_p;

        return $this;
    }

    public function getContenueP(): ?string
    {
        return $this->contenue_p;
    }

    public function setContenueP(string $contenue_p): static
    {
        $this->contenue_p = $contenue_p;

        return $this;
    }

    public function getDatecrP(): ?\DateTimeInterface
    {
        return $this->datecr_p;
    }

    public function setDatecrP(\DateTimeInterface $datecr_p): static
    {
        $this->datecr_p = $datecr_p;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPublication($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPublication() === $this) {
                $comment->setPublication(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->titre_p;
    }

}
