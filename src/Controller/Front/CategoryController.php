<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CategoryController extends AbstractController
{
    /**
     * @Route("/view/{slug}", name="product_category") //! Préfixer pb de route => view en prefix pour voir la category
     */
    public function category($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        // dd($category);


        /**
         *! Page d'erreur
         */
        // if(!$category){throw new NotFoundHttpException("La catégorie demandée n'existe pas");}  //? Existe aussi d'une autre façon
        if (!$category)
        {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig',
        [
            'slug' => $slug,
            'category' => $category,
        ]);
    }

}
