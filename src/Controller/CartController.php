<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/add/{id}", name="add", methods={"GET", "POST"}, requirements={"id":"\d+"}) //?nombre only
     */
    public function add($id, SessionInterface $session, ProductRepository $productRepository)
    {
        // dd($id);

        // Chercher le panier dans la session, sous forme de tableau
        $cart = $session->get('cart', []); // si rien, tableau vide

        // Est-ce que le produit existe dans la DB ?
        $product = $productRepository->find($id);

        if (!$product) // Si le produit n'existe PAS dans la db
        {
            throw $this->createNotFoundException("Le produit $id n'existe pas"); // msg d'erreur
        }

        if (array_key_exists($id, $cart)) // Si le produit est dans le panier
        {
            $cart[$id]++; // Rajouter le produit
        }
        else // si le produit n'est PAS dans le panier
        {
            $cart[$id] = 1; // Ajouter le produit dans le panier
        }


        $session->set('cart', $cart);

        // $session->remove('cart'); //? Phase dev => remet à ZERO le panier de la session

        // dd($session->get('cart'));

        return $this->redirectToRoute('product_show',
        [
            'category_slug' => $product->getSlug(), // slug de la catégorie
            'slug' => $product->getSlug() // slug du produit
        ]);
    }
}
