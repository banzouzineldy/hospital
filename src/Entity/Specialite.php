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

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: User::class,orphanRemoval:true)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: User::class)]
    private Collection $user;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Rdvs::class)]
    private Collection $rdvs;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->rdvs = new ArrayCollection();
     
    
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
    * @return Collection<int, Rendezvous>
    */

   /**
    * @return Collection<int, User>
    */

   /**
    * @return Collection<int, User>
    */

   /**
    * @return Collection<int, User>
    */

   /**
    * @return Collection<int, User>
    */
   public function getUser(): Collection
   {
       return $this->user;
   }

   public function addUser(User $user): self
   {
       if (!$this->user->contains($user)) {
           $this->user->add($user);
           $user->setSpecialite($this);
       }

       return $this;
   }

   public function removeUser(User $user): self
   {
       if ($this->user->removeElement($user)) {
           // set the owning side to null (unless already changed)
           if ($user->getSpecialite() === $this) {
               $user->setSpecialite(null);
           }
       }

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
           $rdv->setSpecialite($this);
       }

       return $this;
   }

   public function removeRdv(Rdvs $rdv): self
   {
       if ($this->rdvs->removeElement($rdv)) {
           // set the owning side to null (unless already changed)
           if ($rdv->getSpecialite() === $this) {
               $rdv->setSpecialite(null);
           }
       }

       return $this;
   }



   

  
}
