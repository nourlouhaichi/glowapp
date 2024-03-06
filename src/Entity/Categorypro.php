<?php

namespace App\Entity;

use App\Repository\CategoryproRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryproRepository::class)]
class Categorypro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categorypro', targetEntity: Programme::class)]
    private Collection $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Programme>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Programme $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setCategorypro($this);
        }

        return $this;
    }

    public function removeProgram(Programme $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getCategorypro() === $this) {
                $program->setCategorypro(null);
            }
        }

        return $this;
    }
}
