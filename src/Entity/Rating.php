<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 1)]
    private ?float $ratingValue = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Programme $programe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingValue(): ?float
    {
        return $this->ratingValue;
    }
    
    public function setRatingValue(float $ratingValue): self
    {
        $this->ratingValue = $ratingValue;
    
        return $this;
    }

    public function getPrograme(): ?Programme
    {
        return $this->programe;
    }

    public function setPrograme(?Programme $programe): static
    {
        $this->programe = $programe;

        return $this;
    }
}
