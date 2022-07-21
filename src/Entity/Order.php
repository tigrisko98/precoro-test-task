<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column()]
    private $user_id = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: ProductToOrder::class)]
    private Collection $productToOrders;

    public function __construct()
    {
        $this->productToOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user_id;
    }

    public function setUser($user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, ProductToOrder>
     */
    public function getProductToOrders(): Collection
    {
        return $this->productToOrders;
    }

    public function addProductToOrder(ProductToOrder $productToOrder): self
    {
        if (!$this->productToOrders->contains($productToOrder)) {
            $this->productToOrders[] = $productToOrder;
            $productToOrder->setOrder($this);
        }

        return $this;
    }

    public function removeProductToOrder(ProductToOrder $productToOrder): self
    {
        if ($this->productToOrders->removeElement($productToOrder)) {
            // set the owning side to null (unless already changed)
            if ($productToOrder->getOrder() === $this) {
                $productToOrder->setOrder(null);
            }
        }

        return $this;
    }
}
