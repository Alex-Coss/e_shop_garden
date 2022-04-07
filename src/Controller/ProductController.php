<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{

    /**
     * @Route("/view/{category_slug}/{slug}", name="product_show") //! Préfixer pb de route => view en prefix pour voir le product
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        // if (!$product)
        // {
        //     throw $this->createNotFoundException("Le produit demandé n'existe pas");
        // }

        return $this->render('product/show.html.twig', ['product' => $product,]);
    }

    /**
     * @Route("/admin/product/create", name="product_create", methods={"GET", "POST"})
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request); // inspecter la requête

        if ($form->isSubmitted() && $form->isValid()) // Si le formulaire est envoyé, & validé
        {
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            // dd($product);

            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute('product_show', 
            [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $formProduct = $form->createView();

        return $this->render('product/create.html.twig', ['formProduct' => $formProduct]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product); //? Creation du formulaire productype && remplissage du form par les datas déjà en db

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();

            // dd($product);

            $em->flush(); //Pas de persist, car l'objet est déjà créé

            // penser à faire modifier le slug en même temps que le name

            // $response->headers->set('Location', $url);
            // $response->setStatusCode(302);

            return $this->redirectToRoute('product_show', 
            [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);

        }

        $formProduct = $form->createView();

        return $this->render('product/edit.html.twig', 
        [
            'product' => $product,
            'formProduct' => $formProduct,
        ]);
    }
}
