<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartRepository")
 */
class Part
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
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $serialNumber;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="devices")
    * @ORM\JoinColumn(nullable=true)
    */
    private $model;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="devices")
    * @ORM\JoinColumn(nullable=true)
    */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $location;

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
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }
}
