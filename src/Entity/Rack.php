<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RackRepository")
 */
class Rack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="text", length=10, nullable=true)
     *
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=10, nullable=true)
     *
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     */
    private $row_name;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PowerSource", mappedBy="rack")
    * @ORM\JoinColumn(nullable=true)
    */
    private $power_sources;

    public function __construct()
    {
        $this->power_sources = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getRackRow(): ?string
    {
        return $this->rack_row;
    }

    public function setRackRow(?string $rack_row): self
    {
        $this->rack_row = $rack_row;

        return $this;
    }

    public function getRowName(): ?string
    {
        return $this->row_name;
    }

    public function setRowName(?string $row_name): self
    {
        $this->row_name = $row_name;

        return $this;
    }

    /**
     * @return Collection|PowerSource[]
     */
    public function getPowerSources(): Collection
    {
        return $this->power_sources;
    }

    public function addPowerSource(PowerSource $powerSource): self
    {
        if (!$this->power_sources->contains($powerSource)) {
            $this->power_sources[] = $powerSource;
            $powerSource->setRack($this);
        }

        return $this;
    }

    public function removePowerSource(PowerSource $powerSource): self
    {
        if ($this->power_sources->contains($powerSource)) {
            $this->power_sources->removeElement($powerSource);
            // set the owning side to null (unless already changed)
            if ($powerSource->getRack() === $this) {
                $powerSource->setRack(null);
            }
        }

        return $this;
    }
}
