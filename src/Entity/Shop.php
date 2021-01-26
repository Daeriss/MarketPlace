<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 */
class Shop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="shop")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="shop")
     */
    private $orders;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy = "shop" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paiement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Lundi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Mardi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Mercredi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Jeudi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Vendredi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Samedi;

    /**
     * @ORM\Column(type="time", length=255, nullable=true)
     */
    private $Dimanche;

    /**
     * @ORM\OneToMany(targetEntity=Services::class, mappedBy="shop")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="shop")
     */
    private $calendars;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $road;

    /**
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $lundiclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $mardiclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $mercrediclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $jeudiclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $vendrediclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $samediclose;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $dimancheclose;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->calendars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setShop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getShop() === $this) {
                $product->setShop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrders(Order $orders): self
    {
        if (!$this->orders->contains($orders)) {
            $this->orders[] = $orders;
            $orders->setShop($this);
        }

        return $this;
    }

    public function removeOrders(Order $orders): self
    {
        if ($this->orders->removeElement($orders)) {
            // set the owning side to null (unless already changed)
            if ($orders->getShop() === $this) {
                $orders->setShop(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPaiement(): ?string
    {
        return $this->paiement;
    }

    public function setPaiement(string $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getLundi(): ?\DateTimeInterface
    {
        return $this->Lundi;
    }

    public function setLundi(?\DateTimeInterface $Lundi): self
    {
        $this->Lundi = $Lundi;

        return $this;
    }

    public function getMardi(): ?\DateTimeInterface
    {
        return $this->Mardi;
    }

    public function setMardi(?\DateTimeInterface $Mardi): self
    {
        $this->Mardi = $Mardi;

        return $this;
    }

    public function getMercredi(): ?\DateTimeInterface
    {
        return $this->Mercredi;
    }

    public function setMercredi(?\DateTimeInterface $Mercredi): self
    {
        $this->Mercredi = $Mercredi;

        return $this;
    }

    public function getJeudi(): ?\DateTimeInterface
    {
        return $this->Jeudi;
    }

    public function setJeudi(?\DateTimeInterface $Jeudi): self
    {
        $this->Jeudi = $Jeudi;

        return $this;
    }

    public function getVendredi(): ?\DateTimeInterface
    {
        return $this->Vendredi;
    }

    public function setVendredi(?\DateTimeInterface $Vendredi): self
    {
        $this->Vendredi = $Vendredi;

        return $this;
    }

    public function getSamedi(): ?\DateTimeInterface
    {
        return $this->Samedi;
    }

    public function setSamedi(?\DateTimeInterface $Samedi): self
    {
        $this->Samedi = $Samedi;

        return $this;
    }

    public function getDimanche(): ?\DateTimeInterface
    {
        return $this->Dimanche;
    }

    public function setDimanche(?\DateTimeInterface $Dimanche): self
    {
        $this->Dimanche = $Dimanche;

        return $this;
    }

    /**
     * @return Collection|Services[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Services $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setShop($this);
        }

        return $this;
    }

    public function removeService(Services $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getShop() === $this) {
                $service->setShop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setShop($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getShop() === $this) {
                $calendar->setShop(null);
            }
        }

        return $this;
    }

    public function getRoad(): ?string
    {
        return $this->road;
    }

    public function setRoad(string $road): self
    {
        $this->road = $road;

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

    public function getLundiclose(): ?\DateTimeInterface
    {
        return $this->lundiclose;
    }

    public function setLundiclose(?\DateTimeInterface $lundiclose): self
    {
        $this->lundiclose = $lundiclose;

        return $this;
    }

    public function getMardiclose(): ?\DateTimeInterface
    {
        return $this->mardiclose;
    }

    public function setMardiclose(?\DateTimeInterface $mardiclose): self
    {
        $this->mardiclose = $mardiclose;

        return $this;
    }

    public function getMercrediclose(): ?\DateTimeInterface
    {
        return $this->mercrediclose;
    }

    public function setMercrediclose(?\DateTimeInterface $mercrediclose): self
    {
        $this->mercrediclose = $mercrediclose;

        return $this;
    }

    public function getJeudiclose(): ?\DateTimeInterface
    {
        return $this->jeudiclose;
    }

    public function setJeudiclose(?\DateTimeInterface $jeudiclose): self
    {
        $this->jeudiclose = $jeudiclose;

        return $this;
    }

    public function getVendrediclose(): ?\DateTimeInterface
    {
        return $this->vendrediclose;
    }

    public function setVendrediclose(?\DateTimeInterface $vendrediclose): self
    {
        $this->vendrediclose = $vendrediclose;

        return $this;
    }

    public function getSamediclose(): ?\DateTimeInterface
    {
        return $this->samediclose;
    }

    public function setSamediclose(?\DateTimeInterface $samediclose): self
    {
        $this->samediclose = $samediclose;

        return $this;
    }

    public function getDimancheclose(): ?\DateTimeInterface
    {
        return $this->dimancheclose;
    }

    public function setDimancheclose(?\DateTimeInterface $dimancheclose): self
    {
        $this->dimancheclose = $dimancheclose;

        return $this;
    }

   
}
