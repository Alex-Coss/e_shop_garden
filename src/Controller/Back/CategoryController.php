<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// BREAD = Browse / Read / Edit / Add / Delete

/**
 * @Route("/admin/category", name="Back_admin_category_")
 */
class CategoryController extends AbstractController
{

    // /**
    //  * @Route("/view", name="index", methods={"GET"})
    //  *//? browse
    // public function index(): Response
    // {
    //     return $this->render('back/category/index.html.twig', [
    //         'controller_name' => 'CategoryController',
    //     ]);
    // }

    // /**
    //  * @Route("/view", name="index", methods={"GET"})
    //  *//? read
    // public function index(): Response
    // {
    //     return $this->render('back/category/index.html.twig', [
    //         'controller_name' => 'CategoryController',
    //     ]);
    // }



    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em)
    {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category); //? Creation du formulaire categorytype && remplissage du form par les datas déjà en db

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();

            // dd($category);

            $em->flush(); //Pas de persist, car l'objet est déjà créé

            // penser à faire modifier le slug en même temps que le name

            return $this->redirectToRoute('homepage');
        }

        $formCategory = $form->createView();

        return $this->render('category/edit.html.twig', 
        [
            'category' => $category,
            'formCategory' => $formCategory,
        ]);
    }




    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request); // inspecter la requête

        if ($form->isSubmitted() && $form->isValid()) // Si le formulaire est envoyé, & validé
        {

            $category->setSlug(strtolower($slugger->slug($category->getName())));

            // dd($category);

            $em->persist($category);
            $em->flush();
            
            return $this->redirectToRoute('homepage');
        }

        $formCategory = $form->createView();

        return $this->render('category/create.html.twig', ['formCategory' => $formCategory]);
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
