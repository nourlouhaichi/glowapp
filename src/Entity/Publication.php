<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[Vich\Uploadable]
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
    #[Assert\NotBlank(message:"Type required ")]
    #[Assert\Choice(choices: ["nutritionist", "coach","Nutritionist","Coach"], message: "choose valid type")]
    private ?string $type_p = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"content required ")]
    #[Assert\Length(min:10,maxMessage:"content too short ")]
    private ?string $contenue_p = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecr_p = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'publication')]
    private Collection $comments;
    #[Vich\UploadableField(mapping: 'img_pub', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'publications', orphanRemoval: true, cascade:["persist"])]
    private Collection $images;
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPublications($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPublications() === $this) {
                $image->setPublications(null);
            }
        }

        return $this;
    }

}
