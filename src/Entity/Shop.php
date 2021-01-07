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
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="shop")
     */
    private $shopProducts;

    public function __construct()
    {
        $this->shopProducts = new ArrayCollection();
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
     * @return Collection|Products[]
     */
    public function getShopProducts(): Collection
    {
        return $this->shopProducts;
    }

    public function addShopProduct(Products $shopProduct): self
    {
        if (!$this->shopProducts->contains($shopProduct)) {
            $this->shopProducts[] = $shopProduct;
            $shopProduct->setShop($this);
        }

        return $this;
    }

    public function removeShopProduct(Products $shopProduct): self
    {
        if ($this->shopProducts->removeElement($shopProduct)) {
            // set the owning side to null (unless already changed)
            if ($shopProduct->getShop() === $this) {
                $shopProduct->setShop(null);
            }
        }

        return $this;
    }
}
