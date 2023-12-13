<?php

namespace App\Entity;

use App\Repository\HospitalisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalisationRepository::class)]
class Hospitalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $typeAdmission = null;

    #[ORM\Column(length: 255)]
    private ?string $motifAdmission = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Pathologie $pathologie = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Chambre $chambre = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Lit $lit = null;


    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Service $service = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable:true)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?Specialite $specialite = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $motifSortie = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    private ?User $agent = null;

   

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeAdmission(): ?string
    {
        return $this->typeAdmission;
    }

    public function setTypeAdmission(string $typeAdmission): self
    {
        $this->typeAdmission = $typeAdmission;

        return $this;
    }

    public function getMotifAdmission(): ?string
    {
        return $this->motifAdmission;
    }

    public function setMotifAdmission(string $motifAdmission): self
    {
        $this->motifAdmission = $motifAdmission;

        return $this;
    }

    public function getPathologie(): ?Pathologie
    {
        return $this->pathologie;
    }

    public function setPathologie(?Pathologie $pathologie): self
    {
        $this->pathologie = $pathologie;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getLit(): ?Lit
    {
        return $this->lit;
    }

    public function setLit(?Lit $lit): self
    {
        $this->lit = $lit;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

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

    public function getMotifSortie(): ?string
    {
        return $this->motifSortie;
    }

    public function setMotifSortie(string $motifSortie): self
    {
        $this->motifSortie = $motifSortie;

        return $this;
    }

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    
}
