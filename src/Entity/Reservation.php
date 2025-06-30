<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id_reservation")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $event_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $event_description;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class)
     * @ORM\JoinColumn(nullable=false, name="area_id",referencedColumnName="id_area")
     */
    private $area_id;

    /**
     * @ORM\ManyToOne(targetEntity=Schedule::class)
     * @ORM\JoinColumn(nullable=false, name="schedule_id",referencedColumnName="id_schedule")
     */
    private $schedule_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false, name="user_id",referencedColumnName="id_user")
     */
    private $user_id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->event_name;
    }

    public function setEventName(string $event_name): self
    {
        $this->event_name = $event_name;

        return $this;
    }

    public function getEventDescription(): ?string
    {
        return $this->event_description;
    }

    public function setEventDescription(?string $event_description): self
    {
        $this->event_description = $event_description;

        return $this;
    }

    public function getAreaId(): ?Area
    {
        return $this->area_id;
    }

    public function setAreaId(?Area $area_id): self
    {
        $this->area_id = $area_id;

        return $this;
    }

    public function getScheduleId(): ?Schedule
    {
        return $this->schedule_id;
    }

    public function setScheduleId(?Schedule $schedule_id): self
    {
        $this->schedule_id = $schedule_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
