<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Doctors::class)]
    private Collection $doctors;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Rendezvous::class)]
    private Collection $rendezvouses;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
        $this->rendezvouses = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Doctors>
     */
     public function getDoctors(): Collection
    {
        return $this->doctors;
    } 

    public function addDoctor(Doctors $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors->add($doctor);
            $doctor->setSpecialite($this);
        }

        return $this;
    } 

   /*  public function removeDoctor(Doctors $doctor): self
    {
        if ($this->doctors->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getSpecialite() === $this) {
                $doctor->setSpecialite(null);
            }
        }

        return $this;
    } */

   /**
    * @return Collection<int, Rendezvous>
    */
   public function getRendezvouses(): Collection
   {
       return $this->rendezvouses;
   }

   public function addRendezvouse(Rendezvous $rendezvouse): self
   {
       if (!$this->rendezvouses->contains($rendezvouse)) {
           $this->rendezvouses->add($rendezvouse);
           $rendezvouse->setSpecialite($this);
       }

       return $this;
   }

   /* public function removeRendezvouse(Rendezvous $rendezvouse): self
   {
       if ($this->rendezvouses->removeElement($rendezvouse)) {
           // set the owning side to null (unless already changed)
           if ($rendezvouse->getSpecialite() === $this) {
               $rendezvouse->setSpecialite(null);
           }
       }

       return $this;
   } */

  
}
