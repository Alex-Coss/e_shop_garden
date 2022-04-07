<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{

    /**
     * @Route("/view/{category_slug}/{slug}", name="product_show", methods={"GET"}) //? Préfixer pb de route => view en prefix pour voir le product
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product)
        {
            throw $this->createNotFoundException("Le produit demandé n'existe pas, contactez-nous !");
        }

        return $this->render('product/show.html.twig', ['product' => $product,]);
    }


}
