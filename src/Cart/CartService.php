<?php

namespace App\Cart;

use App\Cart\CartItem;
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

    protected function getCart(): array
    {
        return $this->session->get('cart', []); //? Récupère les données de la session (panier)
    }

    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart); //? met à jour les données de la session (panier)
    }

    public function add(int $id)
    {
        // Chercher le panier dans la session, sous forme de tableau
        $cart = $this->getCart(); // si rien, tableau vide

        if (array_key_exists($id, $cart)) // Si le produit est dans le panier
        {
            $cart[$id]++; // Rajouter le produit
        }
        else // si le produit n'est PAS dans le panier
        {
            $cart[$id] = 1; // Ajouter le produit dans le panier
        }

        // Enregistrer le tout
        $this->saveCart($cart);
    }


    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart(); // Avoir le panier de la session en cours

        if (!array_key_exists($id, $cart)) // Si PAS $id dans le panier, rien à faire, poursuivre.
        {
            return;
        }
        
        // Si $id est dans le panier alors :
        if ($cart[$id] === 1) // si ID = 1
        {
            $this->remove($id); // on supprime
            return; // on continue
        }

        // Si $id n'est pas =1 (donc +), alors
        $cart[$id]--; // -1 $id (decrement / réduire)

        $this->saveCart($cart); // mettre à jour le panier avec les infos MAJ
        
    }


    // Avoir le total d'un panier
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $quantity)
        {
            $product = $this->productRepository->find($id);

            if (!$product) 
            {
                continue;
            }

            $total += $product->getPrice() * $quantity;
        }

        return $total;

    }


    /**
     * Avoir le détail d'un panier par articles (tableau)
     *
     * @return CartItem[]
     */
    public function getDetailItems(): array
    {
        $detailCart = [];

        foreach ($this->getCart() as $id => $quantity)
        {
            $product = $this->productRepository->find($id);

            if (!$product) 
            {
                continue;
            }

            $detailCart[] = new CartItem($product, $quantity);
        }
        
        return $detailCart;
    }

    public function empty()
    {
        $this->saveCart([]);
    }
}