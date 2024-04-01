<?php

namespace App\Entity;

use App\Repository\CategorieProdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: CategorieProdRepository::class)]
class CategorieProd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom categorie doit etre non vide !")]
    #[Assert\Length( min : 5,minMessage :"Entrer un nom au min de 5 caracteres")]
    private ?string $nomCa = null;

   
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $CreateDateCa = null;

    #[ORM\OneToMany(mappedBy: 'categorieProd', targetEntity: Produit::class)]
    private Collection $Produits;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message:"description doit etre non vide !")]
    #[Assert\Length( min :10 ,minMessage :"Entrer une description au min de 10 caracteres")]
    private ?string $descriptionCat = null;

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

    public function getDescriptionCat(): ?string
    {
        return $this->descriptionCat;
    }

    public function setDescriptionCat(?string $descriptionCat): static
    {
        $this->descriptionCat = $descriptionCat;

        return $this;
    }
    public function __toString()
    {
        return $this->nomCa; // ou tout autre champ que vous voulez afficher
    }
}
