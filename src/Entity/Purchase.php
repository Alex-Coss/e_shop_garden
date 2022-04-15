<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';
    // Écrire ces constantes, permet d'appeler n'importe-ou celles-ci, et d'éviter d'éventuelles fautes d'ORTH

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName; //le nom sur le colis

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionnalAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone1;

    /**
     * @ORM\Column(type="integer")
     */
    private $total; // €

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'PENDING'; // pending = en attente
    // commande complète ? Envoyée ? etc...

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $purchaseAt;

    /**
     * @ORM\OneToMany(targetEntity=ItemPurchase::class, mappedBy="purchase", orphanRemoval=true)
     */
    private $itemPurchases;

    public function __construct()
    {
        $this->itemPurchases = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAdditionnalAddress(): ?string
    {
        return $this->additionnalAddress;
    }

    public function setAdditionnalAddress(?string $additionnalAddress): self
    {
        $this->additionnalAddress = $additionnalAddress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(?string $phone1): self
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPurchaseAt(): ?\DateTimeInterface
    {
        return $this->purchaseAt;
    }

    public function setPurchaseAt(\DateTimeInterface $purchaseAt): self
    {
        $this->purchaseAt = $purchaseAt;

        return $this;
    }

    /**
     * @return Collection<int, ItemPurchase>
     */
    public function getItemPurchases(): Collection
    {
        return $this->itemPurchases;
    }

    public function addItemPurchase(ItemPurchase $itemPurchase): self
    {
        if (!$this->itemPurchases->contains($itemPurchase)) {
            $this->itemPurchases[] = $itemPurchase;
            $itemPurchase->setPurchase($this);
        }

        return $this;
    }

    public function removeItemPurchase(ItemPurchase $itemPurchase): self
    {
        if ($this->itemPurchases->removeElement($itemPurchase)) {
            // set the owning side to null (unless already changed)
            if ($itemPurchase->getPurchase() === $this) {
                $itemPurchase->setPurchase(null);
            }
        }

        return $this;
    }
}
