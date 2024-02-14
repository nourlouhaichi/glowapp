<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idp = null;

    #[ORM\Column]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_p = null;

    #[ORM\Column(length: 255)]
    private ?string $description_p = null;

    #[ORM\Column(length: 255)]
    private ?string $type_p = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu_p = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecr_p = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'idr')]
    private Collection $idav;

    public function __construct()
    {
        $this->idav = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function setIdp(int $idp): static
    {
        $this->idp = $idp;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
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

    public function getDescriptionP(): ?string
    {
        return $this->description_p;
    }

    public function setDescriptionP(string $description_p): static
    {
        $this->description_p = $description_p;

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

    public function getContenuP(): ?string
    {
        return $this->contenu_p;
    }

    public function setContenuP(string $contenu_p): static
    {
        $this->contenu_p = $contenu_p;

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
     * @return Collection<int, Avis>
     */
    public function getIdav(): Collection
    {
        return $this->idav;
    }

    public function addIdav(Avis $idav): static
    {
        if (!$this->idav->contains($idav)) {
            $this->idav->add($idav);
            $idav->setIdr($this);
        }

        return $this;
    }

    public function removeIdav(Avis $idav): static
    {
        if ($this->idav->removeElement($idav)) {
            // set the owning side to null (unless already changed)
            if ($idav->getIdr() === $this) {
                $idav->setIdr(null);
            }
        }

        return $this;
    }
}
