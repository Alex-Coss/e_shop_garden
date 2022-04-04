<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_p;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_p;

    /**
     * @ORM\Column(type="text")
     */
    private $description_p;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture_p;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug_p;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameP(): ?string
    {
        return $this->name_p;
    }

    public function setNameP(string $name_p): self
    {
        $this->name_p = $name_p;

        return $this;
    }

    public function getPriceP(): ?int
    {
        return $this->price_p;
    }

    public function setPriceP(int $price_p): self
    {
        $this->price_p = $price_p;

        return $this;
    }

    public function getDescriptionP(): ?string
    {
        return $this->description_p;
    }

    public function setDescriptionP(string $description_p): self
    {
        $this->description_p = $description_p;

        return $this;
    }

    public function getPictureP(): ?string
    {
        return $this->picture_p;
    }

    public function setPictureP(string $picture_p): self
    {
        $this->picture_p = $picture_p;

        return $this;
    }

    public function getSlugP(): ?string
    {
        return $this->slug_p;
    }

    public function setSlugP(string $slug_p): self
    {
        $this->slug_p = $slug_p;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
