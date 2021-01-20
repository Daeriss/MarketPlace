<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @ORM\Column(type="datetime")
     */
    private $CollectDate;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $OrderStatus;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="orderDetails", cascade={"persist", "remove"})
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=SubOrder::class, mappedBy="orderDetails",cascade={"persist"})
     */
    private $subOrders;

    

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->jointures = new ArrayCollection();
        $this->subOrders = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
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
         }

         return $this;
     }

     public function removeProduct(Product $product): self
     {
         $this->product->removeElement($product);

         return $this;
     }

    public function getCollectDate(): ?\DateTimeInterface
    {
        return $this->CollectDate;
    }

    public function setCollectDate(\DateTimeInterface $CollectDate): self
    {
        $this->CollectDate = $CollectDate;

        return $this;
    }


    public function getOrderStatus(): ?string
    {
        return $this->OrderStatus;
    }

    public function setOrderStatus(string $OrderStatus): self
    {
        $this->OrderStatus = $OrderStatus;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(Order $orders): self
    {
        // set the owning side of the relation if necessary
        if ($orders->getOrderDetails() !== $this) {
            $orders->setOrderDetails($this);
        }

        $this->orders = $orders;

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
            $subOrder->setOrderDetails($this);
        }

        return $this;
    }

    public function removeSubOrder(SubOrder $subOrder): self
    {
        if ($this->subOrders->removeElement($subOrder)) {
            // set the owning side to null (unless already changed)
            if ($subOrder->getOrderDetails() === $this) {
                $subOrder->setOrderDetails(null);
            }
        }

        return $this;
    }

    
    
}
