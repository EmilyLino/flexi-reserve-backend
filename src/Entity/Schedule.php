<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id_schedule")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $hour_ini;

    /**
     * @ORM\Column(type="time")
     */
    private $hour_fin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creation_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modification_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHourIni(): ?\DateTimeInterface
    {
        return $this->hour_ini;
    }

    public function setHourIni(\DateTimeInterface $hour_ini): self
    {
        $this->hour_ini = $hour_ini;

        return $this;
    }

    public function getHourFin(): ?\DateTimeInterface
    {
        return $this->hour_fin;
    }

    public function setHourFin(\DateTimeInterface $hour_fin): self
    {
        $this->hour_fin = $hour_fin;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getCreationUser(): ?string
    {
        return $this->creation_user;
    }

    public function setCreationUser(string $creation_user): self
    {
        $this->creation_user = $creation_user;

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

    public function getModificationUser(): ?string
    {
        return $this->modification_user;
    }

    public function setModificationUser(?string $modification_user): self
    {
        $this->modification_user = $modification_user;

        return $this;
    }
}
