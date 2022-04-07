<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {

        $products = $productRepository->findBy([], [], 4);
        
        $category = $categoryRepository->findAll();

        // dd($products);

        return $this->render('home.html.twig',
        [
            'products' => $products,
            'category' => $category,
        ]);
    }
}
