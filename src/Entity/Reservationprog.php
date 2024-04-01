<?php

namespace App\Entity;

use App\Repository\ReservationprogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationprogRepository::class)]
class Reservationprog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrplace = null;

    #[ORM\Column]
    private ?bool $approuve = null;

    #[ORM\ManyToOne(inversedBy: 'reservationprogs')]
    private ?Programme $idprog = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): static
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }

    public function isApprouve(): ?bool
    {
        return $this->approuve;
    }

    public function setApprouve(bool $approuve): static
    {
        $this->approuve = $approuve;

        return $this;
    }

    public function getIdprog(): ?Programme
    {
        return $this->idprog;
    }

    public function setIdprog(?Programme $idprog): static
    {
        $this->idprog = $idprog;

        return $this;
    }
}
