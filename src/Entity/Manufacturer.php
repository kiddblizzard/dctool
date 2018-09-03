<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManufacturerRepository")
 */
class Manufacturer
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
     * @ORM\Column(type="text", length=100)
     *
     */

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Model", mappedBy="manufacturer")
     */
     private $models;

     public function __construct()
     {
         $this->models = new ArrayCollection();
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
     * @param string $manufacturer
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setManufacturer($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            // set the owning side to null (unless already changed)
            if ($model->getManufacturer() === $this) {
                $model->setManufacturer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
