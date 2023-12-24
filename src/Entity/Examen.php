<?php

namespace App\Entity;

use App\Repository\ExamenRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenRepository::class)]
class Examen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $patient = null;

    #[ORM\Column(type:'datetime_immutable',options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $dateEnregistrement = null;

    #[ORM\OneToMany(mappedBy: 'examen', targetEntity: ActeMedical::class)]
    private Collection $acteMedicals;

     public function __construct (){
        $this->dateEnregistrement= new \DateTimeImmutable();
        $this->acteMedicals = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getDateEnregistrement(): ?\DateTimeImmutable
    {
        return $this->dateEnregistrement;
    }

    public function setDateEnregistrement(\DateTimeImmutable $dateEnregistrement): self
    {
        $this->dateEnregistrement = $dateEnregistrement;

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
            $acteMedical->setExamen($this);
        }

        return $this;
    }

    public function removeActeMedical(ActeMedical $acteMedical): self
    {
        if ($this->acteMedicals->removeElement($acteMedical)) {
            // set the owning side to null (unless already changed)
            if ($acteMedical->getExamen() === $this) {
                $acteMedical->setExamen(null);
            }
        }

        return $this;
    }
}
