<?php

namespace App\Entity;

use App\Repository\PlagehoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlagehoraireRepository::class)]
class Plagehoraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $start = null;

    #[ORM\Column(length: 255)]
    private ?string $end = null;

    #[ORM\Column(length: 255)]
    private ?string $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'plagehoraires')]
    private ?User $utilisateurs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function setStart(string $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function setEnd(string $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getUtilisateurs(): ?User
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?User $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }
}
