<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;
    #[ORM\Id]
    #[ORM\Column(length: 8)]
    private ?string $cin = null;

    // #[ORM\ManyToOne]
    // #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'cin', referencedColumnName: 'cin', nullable: false)]
    private ?User $user = null;

    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->cin = $user->getCin();
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function getUser(): object
    {
        return $this->user;
    }
}