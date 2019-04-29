<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallationRepository")
 */
class Installation
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
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $project_name;

    /**
     * @var string
     *
     * @ORM\Column(type="date")
     *
     */
    private $planned_date;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $trellis_ids;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $change_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $sltn;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $po;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(?string $project_name): self
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getPlannedDate(): ?\DateTimeInterface
    {
        return $this->planned_date;
    }

    public function setPlannedDate(\DateTimeInterface $planned_date): self
    {
        $this->planned_date = $planned_date;

        return $this;
    }

    public function getHostname(): ?array
    {
        return $this->hostname;
    }

    public function setHostname(?array $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getLocation(): ?array
    {
        return $this->location;
    }

    public function setLocation(?array $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getTrellisIds(): ?array
    {
        return $this->trellis_ids;
    }

    public function setTrellisIds(?array $trellis_ids): self
    {
        $this->trellis_ids = $trellis_ids;

        return $this;
    }

    public function getChangeId(): ?string
    {
        return $this->change_id;
    }

    public function setChangeId(?string $change_id): self
    {
        $this->change_id = $change_id;

        return $this;
    }

    public function getSltn(): ?string
    {
        return $this->sltn;
    }

    public function setSltn(?string $sltn): self
    {
        $this->sltn = $sltn;

        return $this;
    }

    public function getPo(): ?string
    {
        return $this->po;
    }

    public function setPo(?string $po): self
    {
        $this->po = $po;

        return $this;
    }

}
