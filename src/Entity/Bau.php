<?php

namespace App\Entity;

use App\Constants\BauTypeOptions;
use App\Constants\BauStatusOptions;
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
     * @ORM\Column(type="string", length=20, nullable=true, unique=true)
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
     * @ORM\Column(type="string", length=20, options={"default" = "pending"})
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="baus")
     * @ORM\JoinColumn(nullable=true)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $inc_array;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $task_array;

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

    public function getIncArray(): ?string
    {
        if (empty($this->inc_array)) {
            return "";
        }
        return implode(";", $this->inc_array);
    }

    public function setIncArray(?string $inc_string): self
    {
        $inc_array = explode(";", $inc_string);
        $this->inc_array = $inc_array;

        return $this;
    }

    public function getIncInArray(): ?array
    {
        return $this->inc_array;
    }

    public function getTaskArray(): ?string
    {
        if (empty($this->task_array)) {
            return "";
        }
        return implode(";", $this->task_array);
    }

    public function setTaskArray(?string $task_string): self
    {
        $task_array = explode(";", $task_string);

        $this->task_array = $task_array;

        return $this;
    }

    public function getTaskInArray(): ?array
    {
        return $this->task_array;
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

    public function getTypeForFront(): ?string
    {
        return BauTypeOptions::getText($this->type);
    }

    public function getStatusForFront(): ?string
    {
        return BauStatusOptions::getText($this->status);
    }
}
