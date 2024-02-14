<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idpan = null;

    #[ORM\Column]
    private ?int $quantity_pan = null;

    #[ORM\Column]
    private ?float $totale_price = null;

    public function getIdpan(): ?int
    {
        return $this->idpan;
    }

    public function getQuantityPan(): ?int
    {
        return $this->quantity_pan;
    }

    public function setQuantityPan(int $quantity_pan): static
    {
        $this->quantity_pan = $quantity_pan;

        return $this;
    }

    public function getTotalePrice(): ?float
    {
        return $this->totale_price;
    }

    public function setTotalePrice(float $totale_price): static
    {
        $this->totale_price = $totale_price;

        return $this;
    }
}
