<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopcartRepository")
 */
class Shopcart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $UserID;

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

    public function getUserID(): ?int
    {
        return $this->UserID;
    }

    public function setUserID(?int $UserID): self
    {
        $this->UserID = $UserID;

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
