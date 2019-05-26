<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=50)
     *
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100)
     *
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100)
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100)
     *
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=255)
     *
     */
    private $address;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Device", mappedBy="site")
    */
    private $devices;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Bau", mappedBy="site")
    */
    private $baus;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->baus = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param mixed $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
            $device->setSite($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->contains($device)) {
            $this->devices->removeElement($device);
            // set the owning side to null (unless already changed)
            if ($device->getSite() === $this) {
                $device->setSite(null);
            }
        }

        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Bau[]
     */
    public function getBaus(): Collection
    {
        return $this->baus;
    }

    public function addBaus(Bau $baus): self
    {
        if (!$this->baus->contains($baus)) {
            $this->baus[] = $baus;
            $baus->setSite($this);
        }

        return $this;
    }

    public function removeBaus(Bau $baus): self
    {
        if ($this->baus->contains($baus)) {
            $this->baus->removeElement($baus);
            // set the owning side to null (unless already changed)
            if ($baus->getSite() === $this) {
                $baus->setSite(null);
            }
        }

        return $this;
    }
}
