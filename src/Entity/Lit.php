<?php

namespace App\Entity;

use App\Repository\LitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitRepository::class)]
class Lit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numLit = null;

    #[ORM\ManyToOne(inversedBy: 'lits')]
    private ?Chambre $chambre = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'lit', targetEntity: Hospitalisation::class)]
    private Collection $hospitalisations;

    public function __construct()
    {
        $this->hospitalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumLit(): ?string
    {
        return $this->numLit;
    }

    public function setNumLit(string $numLit): self
    {
        $this->numLit = $numLit;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Hospitalisation>
     */
    public function getHospitalisations(): Collection
    {
        return $this->hospitalisations;
    }

    public function addHospitalisation(Hospitalisation $hospitalisation): self
    {
        if (!$this->hospitalisations->contains($hospitalisation)) {
            $this->hospitalisations->add($hospitalisation);
            $hospitalisation->setLit($this);
        }

        return $this;
    }

    public function removeHospitalisation(Hospitalisation $hospitalisation): self
    {
        if ($this->hospitalisations->removeElement($hospitalisation)) {
            // set the owning side to null (unless already changed)
            if ($hospitalisation->getLit() === $this) {
                $hospitalisation->setLit(null);
            }
        }

        return $this;
    }
}
