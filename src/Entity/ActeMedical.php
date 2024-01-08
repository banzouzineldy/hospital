<?php

namespace App\Entity;

use App\Repository\ActeMedicalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActeMedicalRepository::class)]
class ActeMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $utilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $soin = null;

    #[ORM\Column(length: 255)]
    private ?string $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $examen = null;
 
    public function getId(): ?int
    {
        return $this->id;
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

    public function getSoin(): ?string
    {
        return $this->soin;
    }

    public function setSoin(string $soin): self
    {
        $this->soin = $soin;

        return $this;
    }

    public function getPatient(): ?string
    {
        return $this->patient;
    }

    public function setPatient(string $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getExamen(): ?string
    {
        return $this->examen;
    }

    public function setExamen(string $examen): self
    {
        $this->examen = $examen;

        return $this;
    }

 

 

  


}
