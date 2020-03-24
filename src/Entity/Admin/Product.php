<?php

namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Keywords;

    /**
     * @ORM\Column(type="integer")
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="integer")
     */
    private $Amount;

    /**
     * @ORM\Column(type="float")
     */
    private $Pprice;

    /**
     * @ORM\Column(type="float")
     */
    private $SPrice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Detail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image5;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->Keywords;
    }

    public function setKeywords(string $Keywords): self
    {
        $this->Keywords = $Keywords;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->Type;
    }

    public function setType(int $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->Amount;
    }

    public function setAmount(int $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getPprice(): ?float
    {
        return $this->Pprice;
    }

    public function setPprice(float $Pprice): self
    {
        $this->Pprice = $Pprice;

        return $this;
    }

    public function getSPrice(): ?float
    {
        return $this->SPrice;
    }

    public function setSPrice(float $SPrice): self
    {
        $this->SPrice = $SPrice;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    public function setDetail(?string $Detail): self
    {
        $this->Detail = $Detail;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->Image1;
    }

    public function setImage1(?string $Image1): self
    {
        $this->Image1 = $Image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->Image2;
    }

    public function setImage2(?string $Image2): self
    {
        $this->Image2 = $Image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->Image3;
    }

    public function setImage3(?string $Image3): self
    {
        $this->Image3 = $Image3;

        return $this;
    }

    public function getImage4(): ?string
    {
        return $this->Image4;
    }

    public function setImage4(?string $Image4): self
    {
        $this->Image4 = $Image4;

        return $this;
    }

    public function getImage5(): ?string
    {
        return $this->Image5;
    }

    public function setImage5(?string $Image5): self
    {
        $this->Image5 = $Image5;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
