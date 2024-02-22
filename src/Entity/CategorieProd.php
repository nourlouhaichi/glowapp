<?php

namespace App\Entity;

use App\Repository\CategorieProdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieProdRepository::class)]
class CategorieProd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCa = null;

    #[ORM\Column(length: 255)]
    private ?string $descripCa = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $CreateDateCa = null;

    #[ORM\OneToMany(mappedBy: 'categorieProd', targetEntity: Produit::class)]
    private Collection $Produits;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCa(): ?string
    {
        return $this->nomCa;
    }

    public function setNomCa(string $nomCa): static
    {
        $this->nomCa = $nomCa;

        return $this;
    }

    public function getDescripCa(): ?string
    {
        return $this->descripCa;
    }

    public function setDescripCa(string $descripCa): static
    {
        $this->descripCa = $descripCa;

        return $this;
    }

    public function getCreateDateCa(): ?\DateTimeInterface
    {
        return $this->CreateDateCa;
    }

    public function setCreateDateCa(\DateTimeInterface $CreateDateCa): static
    {
        $this->CreateDateCa = $CreateDateCa;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->Produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->Produits->contains($produit)) {
            $this->Produits->add($produit);
            $produit->setCategorieProd($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->Produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorieProd() === $this) {
                $produit->setCategorieProd(null);
            }
        }

        return $this;
    }
}
