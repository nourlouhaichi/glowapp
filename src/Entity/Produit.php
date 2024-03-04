<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ref = null;

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom produit doit etre non vide !")]
    #[Assert\Length( min : 5,minMessage :"Entrer un nom au min de 5 caracteres")]
    private ?string $name = null;

 
    #[ORM\Column]
    #[Assert\NotBlank(message:"ajouter le prix du produit !")]
    #[Assert\Positive(message: "le prix doit etre possitif")]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"ajouter le stock !")]
    #[Assert\Range(min : 1 , max : 1000 , notInRangeMessage: "le stock doit etre entre {{ min }} et {{ max }} ")]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    

    #[ORM\ManyToOne(inversedBy: 'Produits')]
    private ?CategorieProd $categorieProd = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"description doit etre non vide !")]
    #[Assert\Length( min :10 ,minMessage :"Entrer une description au min de 10 caracteres")]
    private ?string $description = null;

    

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function setRef(int $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }



    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

   
    

    public function getCategorieProd(): ?CategorieProd
    {
        return $this->categorieProd;
    }

    public function setCategorieProd(?CategorieProd $categorieProd): static
    {
        $this->categorieProd = $categorieProd;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
   
}
