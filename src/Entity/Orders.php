<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
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
    private $Total;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Address;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ShipInfo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Note;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $OrderDate;

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

    public function getTotal(): ?int
    {
        return $this->Total;
    }

    public function setTotal(?int $Total): self
    {
        $this->Total = $Total;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getShipInfo(): ?string
    {
        return $this->ShipInfo;
    }

    public function setShipInfo(?string $ShipInfo): self
    {
        $this->ShipInfo = $ShipInfo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(?string $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

    public function getOrderDate(): ?string
    {
        return $this->OrderDate;
    }

    public function setOrderDate(?string $OrderDate): self
    {
        $this->OrderDate = $OrderDate;

        return $this;
    }
}
