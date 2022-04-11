<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    protected $productRepository;
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }


    /**
     * @Route("/", name="browse", methods={"GET"})
     */ //?browse
    public function show(): Response
    {
        $detailCart = $this->cartService->getDetailItems();

        $total = $this->cartService->getTotal();

        return $this->render('cart/browse.html.twig', 
    [
        'items' => $detailCart,
        'total' => $total
    ]);
    }



    /**
     * @Route("/add/{id}", name="add", methods={"GET", "POST"}, requirements={"id":"\d+"}) //?nombre only
     */ //? add
    public function add($id, Request $request)
    {
        // Est-ce que le produit existe dans la DB ?
        $product = $this->productRepository->find($id);

        if (!$product) // Si le produit n'existe PAS dans la db
        {
            throw $this->createNotFoundException("Le produit $id n'existe pas"); // msg d'erreur
        }

        $this->cartService->add($id);

        // $session->remove('cart'); //? Phase dev => remet à ZERO le panier de la session


        // addFlash est un raccourci AbstractController de flashBag
        $this->addFlash('success', 'Le produit a bien été rajouté au panier !');
        // $this->addFlash('warning', 'Attention, ya eu un problème');
        $this->addFlash('info', 'Pour info, c\'est fait');
        // $this->addFlash('warning', 'Attention, ya eu un problème V2');
        // $this->addFlash('danger', 'Attention, ya eu un problème again !');


        // dd($session->get('cart'));

        if ($request->query->get('returnCart'))
        {
            return $this->redirectToRoute("cart_browse");
        }

        return $this->redirectToRoute('product_show',
        [
            'category_slug' => $product->getCategory()->getSlug(), // slug de la catégorie
            'slug' => $product->getSlug() // slug du produit
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete", requirements={"id": "\d+"})
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id); // chercher le produit par ID

        if (!$product) // Si pas de produit, pas de suppression
        {
            throw $this->createNotFoundException("Le produit $id n'as pas pu être trouvé");
        }

        $this->cartService->remove($id); // supprime le produit

        $this->addFlash("success", "Le produit est bien supprimé"); // msg flash

        return $this->redirectToRoute("cart_browse"); // redirection
    }

    
    /**
     * @Route("/decrement/{id}", name="decrement", requirements={"id": "\d+"})
     *? Réduire le nombre de produit dans le panier
     */
    public function decrement($id)
    {
        $product = $this->productRepository->find($id); // chercher le produit par ID

        if (!$product) // Si pas de produit, pas de suppression
        {
            throw $this->createNotFoundException("Le produit $id n'as pas pu être trouvé");
        }

        $this->cartService->decrement($id);

        $this->addFlash("success", "Le produit a bien été retiré");

        return $this->redirectToRoute("cart_browse");
    }
}
