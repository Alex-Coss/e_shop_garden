<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add
            (
                'fullName',
                TextType::class,
                [
                    'label' => 'Nom complet',
                    'attr' => ['placeholder' => 'Ici, votre nom pour la livraison'],
                ]
            )
            ->add
            (
                'address',
                TextType::class,
                [
                    'label' => 'Adresse complète',
                    'attr' => ['placeholder' => 'Ici, votre adresse pour la livraison'],
                ]
            )
            ->add
            (
                'additionnalAddress',
                TextType::class,
                [
                    'label' => 'Adresse complète supplémentaire',
                    'attr' => ['placeholder' => 'Ici, votre adresse pour la livraison'],
                ]
            )
            ->add
            (
                'zipCode',
                TextType::class,
                [
                    'label' => 'Code postal',
                    'attr' => ['placeholder' => 'Ici, le code postal'],
                ]
            )
            ->add
            (
                'city',
                TextType::class,
                [
                    'label' => 'La ville',
                    'attr' => ['placeholder' => 'Ici, la ville pour la livraison'],
                ]
            )
            ->add
            (
                'country',
                TextType::class,
                [
                    'label' => 'Votre Pays',
                    'attr' => ['placeholder' => 'Ici, le Pays, si pas en France'],
                ]
            )
            ->add
            (
                'phone1',
                TextType::class,
                [
                    'label' => 'Numéro de téléphone',
                    'attr' => ['placeholder' => 'Ici, votre n° téléphone'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults
        ([
            // Configure your form options here
            'data_class' => Purchase::class
        ]);
    }
}
