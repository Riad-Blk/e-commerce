<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'action' => 'produits_index',
                'attr' => [
                    //'class' => 'form-control',
                    'placeholder' => 'Entrez un terme'
                ],
                'required' => false,
            ])
            /* ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])*/
            ->add('Rechercher', SubmitType::class,[
                'attr' => [
                    'class' => 'btn-primary',
                ]
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
