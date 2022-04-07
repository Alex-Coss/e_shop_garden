<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add
            (
                'name',
                TextType::class,
                [
                    'label' => 'Nom du produit',
                    'attr' => ['placeholder' => 'Ici, le nom du produit'],
                ]
            )
            ->add
            (
                'price',
                MoneyType::class,
                [
                    'label' => 'Prix du produit',
                    'attr' => ['placeholder' => 'Écrivez ici le prix en €uros !'],
                    // 'currency' => false, // retrait du symbole de la devise //? Commenté car le template Bootstrap change la localisation du symbole
                    'divisor' => 100,
                ]
            )
            ->add
            (
                'description',
                TextareaType::class,
                [
                    'label' => 'Courte description',
                    'attr' => ['placeholder' => 'Écrivez une description courte mais frappante !'],
                ]
            )
            ->add
            (
                'picture',
                UrlType::class,
                [
                    'label' => 'Image du produit',
                    'attr' => ['placeholder' => 'Url de l\'image'],
                ]
            )
            ->add
            (
                'category',
                EntityType::class,
                [
                    'label' => 'Catégorie',
                    'attr' => ['class' => 'form-control',],
                    'placeholder' => '-- Choisir une catégorie --',
                    'class' => Category::class,
                    'choice_label' => 'name'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults
        ([
            'data_class' => Product::class,
        ]);
    }
}
