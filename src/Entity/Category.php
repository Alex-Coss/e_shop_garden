<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug_c;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameC(): ?string
    {
        return $this->name_c;
    }

    public function setNameC(string $name_c): self
    {
        $this->name_c = $name_c;

        return $this;
    }

    public function getSlugC(): ?string
    {
        return $this->slug_c;
    }

    public function setSlugC(string $slug_c): self
    {
        $this->slug_c = $slug_c;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product))
        {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product))
        {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this)
            {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
