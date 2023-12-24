<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Rdvs::class,orphanRemoval:true)]
    private Collection $rdvs;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\ManyToOne(inversedBy: 'patient')]
    private ?Nationalite $nationalite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateEnregistrement = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: ActeMedical::class)]
    private Collection $examen;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: ActeMedical::class)]
    private Collection $acteMedicals;
    public function __construct()
    {
    $this->rdvs = new ArrayCollection();
    $this->dateEnregistrement=new DateTimeImmutable();
    $this->examen = new ArrayCollection();
    $this->acteMedicals = new ArrayCollection();
   
    }

    #[ORM\ManyToOne(inversedBy: 'patient')]
    #[ORM\JoinColumn(nullable: false)]
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Rdvs>
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdvs $rdv): self
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs->add($rdv);
            $rdv->setPatient($this);
        }

        return $this;
    }

    public function removeRdv(Rdvs $rdv): self
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getPatient() === $this) {
                $rdv->setPatient(null);
            }
        }

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

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

    /**
     * @return Collection<int, ActeMedical>
     */
    public function getExamen(): Collection
    {
        return $this->examen;
    }

    public function addExaman(ActeMedical $examan): self
    {
        if (!$this->examen->contains($examan)) {
            $this->examen->add($examan);
            $examan->setPatient($this);
        }

        return $this;
    }

    public function removeExaman(ActeMedical $examan): self
    {
        if ($this->examen->removeElement($examan)) {
            // set the owning side to null (unless already changed)
            if ($examan->getPatient() === $this) {
                $examan->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActeMedical>
     */
    public function getActeMedicals(): Collection
    {
        return $this->acteMedicals;
    }

    public function addActeMedical(ActeMedical $acteMedical): self
    {
        if (!$this->acteMedicals->contains($acteMedical)) {
            $this->acteMedicals->add($acteMedical);
            $acteMedical->setPatient($this);
        }

        return $this;
    }

    public function removeActeMedical(ActeMedical $acteMedical): self
    {
        if ($this->acteMedicals->removeElement($acteMedical)) {
            // set the owning side to null (unless already changed)
            if ($acteMedical->getPatient() === $this) {
                $acteMedical->setPatient(null);
            }
        }

        return $this;
    }

   

  
}
