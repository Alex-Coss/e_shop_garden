<?php

namespace App\Controller;

use App\Cart\CartService;
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
     * @Route("/", name="browse", methods={"GET"})
     */ //?browse
    public function show(CartService $cartService): Response
    {
        $detailCart = $cartService->getDetailItems();

        $total = $cartService->getTotal();

        return $this->render('cart/browse.html.twig', 
    [
        'items' => $detailCart,
        'total' => $total
    ]);
    }



    /**
     * @Route("/add/{id}", name="add", methods={"GET", "POST"}, requirements={"id":"\d+"}) //?nombre only
     */ //? add
    public function add($id, ProductRepository $productRepository, CartService $cartService)
    {
        // Est-ce que le produit existe dans la DB ?
        $product = $productRepository->find($id);

        if (!$product) // Si le produit n'existe PAS dans la db
        {
            throw $this->createNotFoundException("Le produit $id n'existe pas"); // msg d'erreur
        }

        $cartService->add($id);

        // $session->remove('cart'); //? Phase dev => remet à ZERO le panier de la session


        // addFlash est un raccourci AbstractController de flashBag
        $this->addFlash('success', 'Le produit a bien été rajouté au panier !');
        // $this->addFlash('warning', 'Attention, ya eu un problème');
        $this->addFlash('info', 'Pour info, c\'est fait');
        // $this->addFlash('warning', 'Attention, ya eu un problème V2');
        // $this->addFlash('danger', 'Attention, ya eu un problème again !');


        // dd($session->get('cart'));

        return $this->redirectToRoute('product_show',
        [
            'category_slug' => $product->getCategory()->getSlug(), // slug de la catégorie
            'slug' => $product->getSlug() // slug du produit
        ]);
    }
}
