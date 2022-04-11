<?php

namespace App\Controller\Back;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// BREAD = Browse / Read / Edit / Add / Delete

/**
 * @Route("/admin/product", name="Back_admin_product_")
 */
class ProductController extends AbstractController
{
    // /**
    //  * @Route("/view", name="index", methods={"GET"})
    //  *//? browse
    // public function index(): Response
    // {
    //     return $this->render('back/product/index.html.twig', [
    //         'controller_name' => 'ProductController',
    //     ]);
    // }

    // /**
    //  * @Route("/view", name="index", methods={"GET"})
    //  *//? read
    // public function index(): Response
    // {
    //     return $this->render('back/product/index.html.twig', [
    //         'controller_name' => 'ProductController',
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}) //! OK
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

        return $this->render('back/product/edit.html.twig', 
        [
            'product' => $product,
            'formProduct' => $formProduct,
        ]);
    }



    /**
     * @Route("/add", name="add", methods={"GET", "POST"}) //!OK
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

        return $this->render('back/product/add.html.twig', ['formProduct' => $formProduct]);
    }


    // /**
    //  * @Route("/view", name="index", methods={"GET"})
    //  *//? delete
    // public function index(): Response
    // {
    //     return $this->render('back/product/index.html.twig', [
    //         'controller_name' => 'ProductController',
    //     ]);
    // }


}
