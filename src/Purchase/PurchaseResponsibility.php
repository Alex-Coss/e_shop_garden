<?php

namespace App\Purchase;

use App\Cart\CartService;
use DateTime;
use App\Entity\Purchase;
use App\Entity\ItemPurchase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Ce fichier a pour but de gérer les responsabilités dans des classes séparée, qui n'est pas du ressort du Controller, mais qui aurait pu être inclus dedans.
 */
class PurchaseResponsibility
{

    protected $security;
    protected $cartService;
    protected $em;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    public function storePurchase(Purchase $purchase)
    {
        // Lier la purchase avec l'USER
        $purchase->setUser($this->security->getUser())
        ->setPurchaseAt(new DateTime())
        ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);

        // Lier les produits dans le panier (CartService)
        foreach ($this->cartService->getDetailItems() as $cartItem)
        {
            $itemPurchase = new ItemPurchase;
            $itemPurchase
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setPurchase($purchase)
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal());

            $this->em->persist($itemPurchase);
        }

        // Enregistrer le tout !
        $this->em->flush();
    }

}