<?php

namespace App\Entity;

use App\Repository\RdvsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RdvsRepository::class)]
class Rdvs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motif = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $emailsmedecin = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Specialite $specialite = null;


    public function getId(): ?int
    {
        return $this->id;
    }
     public function __construct(){
       
     }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getEmailsmedecin(): ?string
    {
        return $this->emailsmedecin;
    }

    public function setEmailsmedecin(string $emailsmedecin): self
    {
        $this->emailsmedecin = $emailsmedecin;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

}
