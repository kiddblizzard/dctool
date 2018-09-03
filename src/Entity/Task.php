<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
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
     * @ORM\Column(type="text")
     *
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="date")
     *
     */
    private $due_date;

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
    private $delivery;

    /**
     * [private description]
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $has_ct_access;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->due_date = $date;

        return $this;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

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

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(?string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getHasCtAccess(): ?bool
    {
        return $this->has_ct_access;
    }

    public function setHasCtAccess(?bool $has_ct_access): self
    {
        $this->has_ct_access = $has_ct_access;

        return $this;
    }


}
