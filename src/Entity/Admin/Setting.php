<?php

namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Keywords;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Company;

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
    private $Email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Facebook;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Instagram;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Twitter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Youtube;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Smtpserver;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Smtpmail;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Smtppass;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Smtpport;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $About;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $Contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->Keywords;
    }

    public function setKeywords(?string $Keywords): self
    {
        $this->Keywords = $Keywords;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(?string $Company): self
    {
        $this->Company = $Company;

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

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->Facebook;
    }

    public function setFacebook(?string $Facebook): self
    {
        $this->Facebook = $Facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->Instagram;
    }

    public function setInstagram(?string $Instagram): self
    {
        $this->Instagram = $Instagram;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->Twitter;
    }

    public function setTwitter(?string $Twitter): self
    {
        $this->Twitter = $Twitter;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->Youtube;
    }

    public function setYoutube(?string $Youtube): self
    {
        $this->Youtube = $Youtube;

        return $this;
    }

    public function getSmtpserver(): ?string
    {
        return $this->Smtpserver;
    }

    public function setSmtpserver(?string $Smtpserver): self
    {
        $this->Smtpserver = $Smtpserver;

        return $this;
    }

    public function getSmtpmail(): ?string
    {
        return $this->Smtpmail;
    }

    public function setSmtpmail(?string $Smtpmail): self
    {
        $this->Smtpmail = $Smtpmail;

        return $this;
    }

    public function getSmtppass(): ?string
    {
        return $this->Smtppass;
    }

    public function setSmtppass(?string $Smtppass): self
    {
        $this->Smtppass = $Smtppass;

        return $this;
    }

    public function getSmtpport(): ?string
    {
        return $this->Smtpport;
    }

    public function setSmtpport(?string $Smtpport): self
    {
        $this->Smtpport = $Smtpport;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->About;
    }

    public function setAbout(?string $About): self
    {
        $this->About = $About;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->Contact;
    }

    public function setContact(?string $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }
}
