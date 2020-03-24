<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersDetailRepository")
 */
class OrdersDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $OrderID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ProductID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderID(): ?int
    {
        return $this->OrderID;
    }

    public function setOrderID(int $OrderID): self
    {
        $this->OrderID = $OrderID;

        return $this;
    }

    public function getProductID(): ?int
    {
        return $this->ProductID;
    }

    public function setProductID(?int $ProductID): self
    {
        $this->ProductID = $ProductID;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(?int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }
}
