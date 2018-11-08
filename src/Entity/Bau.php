<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BauRepository")
 */
class Bau
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
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     */
    private $chg_number;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     *
     */
    private $start_time;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     *
     */
    private $end_time;

    /**
     * @var string
     * new/completed/
     *
     * @ORM\Column(type="string", length=20, options={"default" = "new"})
     *
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $remark;

    /**
     * [private description]
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * [private description]
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $vendor;

    /**
     * [private description]
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $cmp;

    /**
     * @ORM\Column(type="integer")
     */
    private $site;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChgNumber(): ?string
    {
        return $this->chg_number;
    }

    public function setChgNumber(?string $chg_number): self
    {
        $this->chg_number = $chg_number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCmp(): ?bool
    {
        return $this->cmp;
    }

    public function setCmp(bool $cmp): self
    {
        $this->cmp = $cmp;

        return $this;
    }

    public function getSite(): ?int
    {
        return $this->site;
    }

    public function setSite(int $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }
}
