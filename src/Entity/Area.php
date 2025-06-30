<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 */
class Area
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id_area")
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $area_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $area_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modification_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creation_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAreaName(): ?string
    {
        return $this->area_name;
    }

    public function getAreaDescription(): ?string
    {
        return $this->area_description;
    }

    public function getCreationDate(): ?DateTime
    {
        return $this->creation_date;
    }

    public function getModificationUser(): ?string
    {
        return $this->modification_user;
    }

    public function getCreationUser(): ?string
    {
        return $this->creation_user;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setAreaName(string $area_name)
    {
        $this->area_name = $area_name;
    }

    public function setAreaDescription(?string $area_description)
    {
        $this->area_description = $area_description;
    }

    public function setCreationDate(?DateTime $creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function setModificationUser(?string $modification_user)
    {
        $this->modification_user = $modification_user;
    }

    public function setCreationUser(?string $creation_user)
    {
        $this->creation_user = $creation_user;
    }

    public function setStatus(?string $status)
    {
        $this->status = $status;
    }
}
