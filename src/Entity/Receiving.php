<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceivingRepository")
 */
class Receiving
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
     * @ORM\Column(type="date")
     *
     */
    private $planned_date;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $delivery_info;

    /**
     * [private description]
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $access;

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
     * receiving/sending
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getDeliveryInfo(): ?string
    {
        return $this->delivery_info;
    }

    public function setDeliveryInfo(?string $delivery_info): self
    {
        $this->delivery_info = $delivery_info;

        return $this;
    }

    public function getAccess(): ?bool
    {
        return $this->access;
    }

    public function setAccess(bool $access): self
    {
        $this->access = $access;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function showHasAccess(): ?string
    {
        return $this->access?"Yes":"No";
    }

    public function showTypeInText(): ?string
    {
        if ($this->type == 'receiving') {
            return 'Device Roll In';
        } else if ($this->type == 'sending') {
            return 'Device Roll Out';
        } else {
            return "";
        }
    }

}
