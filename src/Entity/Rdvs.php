<?php

namespace App\Entity;

use App\Repository\RdvsRepository;
use DateTimeImmutable;
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

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motif = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $emailsmedecin = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Specialite $specialite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE ,options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateEnregistrement = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Doctors $docteur = null;

   /*  #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateautomatique = null; */

     #[ORM\Column(type: Types::DATE_IMMUTABLE,options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateautomatique = null; 

   /*  #[ORM\Column(type: Types::DATE_IMMUTABLE,options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateEnregistrement = null; */
 
    public function getId(): ?int
    {
        return $this->id;
    }
     public function __construct(){
       $this->dateEnregistrement= new DateTimeImmutable();
       $this->dateautomatique= new \DateTimeImmutable();
       
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

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->dateEnregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $dateEnregistrement): self
    {
        $this->dateEnregistrement = $dateEnregistrement;

        return $this;
    }

    public function getDocteur(): ?Doctors
    {
        return $this->docteur;
    }

    public function setDocteur(?Doctors $docteur): self
    {
        $this->docteur = $docteur;

        return $this;
    }

   /*  public function getDateautomatique(): ?\DateTimeImmutable
    {
        return $this->dateautomatique;
    }

    public function setDateautomatique(\DateTimeImmutable $dateautomatique): self
    {
        $this->dateautomatique = $dateautomatique;

        return $this;
    } */

   public function getDateautomatique(): ?\DateTimeImmutable
   {
       return $this->dateautomatique;
   }

   public function setDateautomatique(\DateTimeImmutable $dateautomatique): self
   {
       $this->dateautomatique = $dateautomatique;

       return $this;
   }

   

   

}
