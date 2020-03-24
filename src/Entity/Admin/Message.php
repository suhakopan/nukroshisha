<?php

namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $Surname;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Messaggetext;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Status;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    public function setSurname(?string $Surname): self
    {
        $this->Surname = $Surname;

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

    public function getSubject(): ?string
    {
        return $this->Subject;
    }

    public function setSubject(?string $Subject): self
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getMessaggetext(): ?string
    {
        return $this->Messaggetext;
    }

    public function setMessaggetext(?string $Messaggetext): self
    {
        $this->Messaggetext = $Messaggetext;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->Status;
    }

    public function setStatus(?int $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
