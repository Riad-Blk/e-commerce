<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => new Length(30, 2),
                'attr' => [
                    'placeholder' => 'Entrer votre nom... ',
                    'class' => '',
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => new Length(30, 2),
                'attr' => [
                    'placeholder' => 'Entrer votre prénom... ',
                    'class' => '',
                ]
            ])
            ->add('phone', TextType::class, [                
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(\d\d\.){4}(\d\d)+$/',
                        'match' => true,
                        'message' => 'Le numéro de téléphone n\'est pas conforme',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '01.27.54.54.72'
                ],
                'help' => 'Uniquement numéro du territoire français'
            ])
            ->add('newsletter', CheckboxType::class, [
                'attr' => [
                    'checked' => true,
                    'class' => 'custom',
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
