<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'placeholder' => 'Sélectionner une catégorie',
            ])
            ->add('produit', EntityType::class, [
                'label' => 'Produits',
                'class' => Produits::class,
                'placeholder' => 'Sélectionner un produit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
