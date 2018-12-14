<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     */
    private $rack_row;

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
}
