<?php

namespace App\Entity;

use App\Repository\CabinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CabinRepository::class)]
class Cabin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price_multiplier = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?float $custom_price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $coordinates = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $miniature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriceMultiplier(): ?float
    {
        return $this->price_multiplier;
    }

    public function setPriceMultiplier(float $price_multiplier): self
    {
        $this->price_multiplier = $price_multiplier;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCustomPrice(): ?float
    {
        return $this->custom_price;
    }

    public function setCustomPrice(?float $custom_price): self
    {
        $this->custom_price = $custom_price;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getMiniature(): ?string
    {
        return $this->coordinates;
    }

    public function setMiniature(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }
}
