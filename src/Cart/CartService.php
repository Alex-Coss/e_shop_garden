<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        // Chercher le panier dans la session, sous forme de tableau
        $cart = $this->session->get('cart', []); // si rien, tableau vide

        if (array_key_exists($id, $cart)) // Si le produit est dans le panier
        {
            $cart[$id]++; // Rajouter le produit
        }
        else // si le produit n'est PAS dans le panier
        {
            $cart[$id] = 1; // Ajouter le produit dans le panier
        }


        $this->session->set('cart', $cart);
    }

    // Avoir le total d'un panier
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $quantity)
        {
            $product = $this->productRepository->find($id);

            $total += $product->getPrice() * $quantity;
        }

        return $total;

    }

    // Avoir le détail d'un panier par articles (tableau)
    public function getDetailItems(): array
    {
        $detailCart = [];

        foreach ($this->session->get('cart', []) as $id => $quantity)
        {
            $product = $this->productRepository->find($id);

            $detailCart[] =
            [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        
        return $detailCart;
    }
}