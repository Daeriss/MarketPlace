<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;


     /**
      * @ORM\OneToMany(targetEntity=SubOrder::class, mappedBy="product", cascade={"persist"})
      */
     private $subOrders;

     /**
      * @ORM\Column(type="boolean")
      */
     private $isAvailable;

    public function __construct()
    {
        $this->subOrders = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

     /**
      * @return Collection|SubOrder[]
      */
     public function getSubOrders(): Collection
     {
         return $this->subOrders;
     }

     public function addSubOrder(SubOrder $subOrder): self
     {
         if (!$this->subOrders->contains($subOrder)) {
             $this->subOrders[] = $subOrder;
             $subOrder->setProduct($this);
         }

         return $this;
     }

     public function removeSubOrder(SubOrder $subOrder): self
     {
         if ($this->subOrders->removeElement($subOrder)) {
             // set the owning side to null (unless already changed)
             if ($subOrder->getProduct() === $this) {
                 $subOrder->setProduct(null);
             }
         }

         return $this;
     }

     public function getIsAvailable(): ?bool
     {
         return $this->isAvailable;
     }

     public function setIsAvailable(bool $isAvailable): self
     {
         $this->isAvailable = $isAvailable;

         return $this;
     }


}
