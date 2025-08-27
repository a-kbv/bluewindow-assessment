<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Brand name is required')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Brand name must be at least {{ limit }} characters', maxMessage: 'Brand name cannot be longer than {{ limit }} characters')]
    private ?string $name = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Url(message: 'Image must be a valid URL')]
    #[Assert\Length(max: 500, maxMessage: 'Image URL cannot be longer than {{ limit }} characters')]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'Rating must be between {{ min }} and {{ max }}')]
    private ?int $rating = null;

    #[ORM\Column(length: 2, nullable: true)]
    #[Assert\Length(exactly: 2, exactMessage: 'Country code must be exactly 2 characters')]
    #[Assert\Regex(pattern: '/^[A-Z]{2}$/', message: 'Country code must be 2 uppercase letters')]
    private ?string $countryCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): static
    {
        $this->countryCode = $countryCode;
        return $this;
    }
}
