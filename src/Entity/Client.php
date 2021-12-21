<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_infos"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"show_infos"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"show_infos"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=14)
     * @Groups({"show_infos"})
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="client", cascade={"persist", "remove"})
     * @Groups({"show_infos"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_infos"})
     */
    private $newsletter;

  
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="client", orphanRemoval=true)
     */
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();

     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="client")
     */
    private $Fav;

    public function __construct()
    {
        $this->Fav = new ArrayCollection();

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getClient() !== $this) {
            $user->setClient($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->getUser()->getClient()->getNom().' '.$this->getUser()->getClient()->getPrenom().' '.$this->getUser()->getClient()->getPhone();
    }

    /**

     * @return Collection|Adresse[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setClient($this);

     * @return Collection|Produits[]
     */
    public function getFav(): Collection
    {
        return $this->Fav;
    }

    public function addFav(Produits $fav): self
    {
        if (!$this->Fav->contains($fav)) {
            $this->Fav[] = $fav;
            $fav->setClient($this);

        }

        return $this;
    }


    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getClient() === $this) {
                $adress->setClient(null);

    public function removeFav(Produits $fav): self
    {
        if ($this->Fav->removeElement($fav)) {
            // set the owning side to null (unless already changed)
            if ($fav->getClient() === $this) {
                $fav->setClient(null);

            }
        }

        return $this;
    }

}
