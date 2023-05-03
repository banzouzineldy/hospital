<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dateRendezvous = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $heureRendezvous = null;

    #[ORM\Column(length: 255)]
    private ?string $patient = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    private ?Specialite $specialite = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    private ?Doctors $doctors = null;

    public function __construct()
    {
     $this ->heureRendezvous= new \DateTimeImmutable() ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRendezvous(): ?string
    {
        return $this->dateRendezvous;
    }

    public function setDateRendezvous(string $dateRendezvous): self
    {
        $this->dateRendezvous = $dateRendezvous;

        return $this;
    }
    public function getHeureRendezvous(): ?\DateTimeInterface
    {
       
        return $this->heureRendezvous;
    }

    public function setHeureRendezvous(\DateTimeInterface  $heureRendezvous): self
    {
        $this-> heureRendezvous = $heureRendezvous;
       

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

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDoctors(): ?Doctors
    {
        return $this->doctors;
    }

    public function setDoctors(?Doctors $doctors): self
    {
        $this->doctors = $doctors;

        return $this;
    }
    

}
