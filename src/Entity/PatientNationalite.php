<?php

namespace App\Entity;

use App\Repository\PatientNationaliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientNationaliteRepository::class)]
class PatientNationalite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?patient $patient = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?nationalite $nationalite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?patient
    {
        return $this->patient;
    }

    public function setPatient(patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getNationalite(): ?nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }
}
